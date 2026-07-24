<?php

declare(strict_types=1);

namespace Modules\Email\Inbox;

use IMAP\Connection;
use Modules\Email\Settings\EmailSettings;
use Safe\Exceptions\ImapException;

use function Safe\imap_body;
use function Safe\imap_check;
use function Safe\imap_fetch_overview;
use function Safe\imap_fetchbody;
use function Safe\imap_fetchheader;
use function Safe\imap_open;
use function Safe\imap_setflag_full;

final readonly class ImapEmailInboxClient implements EmailInboxClientInterface
{
    public function __construct(private EmailSettings $settings) {}

    public function fetchUnread(int $limit): array
    {
        return $this->fetchFromFolder($this->settings->imapFolder(), 'UNSEEN', $limit, 'inbound');
    }

    public function fetchSent(int $limit): array
    {
        if (! $this->settings->pollSentEnabled()) {
            return [];
        }

        return $this->fetchFromFolder($this->settings->imapSentFolder(), 'ALL', $limit, 'outbound');
    }

    public function markAsSeen(string $uid): void
    {
        $mailbox = $this->openMailbox($this->settings->imapFolder());
        imap_setflag_full($mailbox, $uid, '\\Seen', ST_UID);
        imap_close($mailbox);
    }

    public function verifyConnection(): array
    {
        $inbox = $this->mailboxStats($this->settings->imapFolder());
        $sent = $this->settings->pollSentEnabled()
            ? $this->mailboxStats($this->settings->imapSentFolder())
            : null;

        return [
            'ok' => true,
            'inbox' => $inbox,
            'sent' => $sent,
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function fetchFromFolder(string $folder, string $criteria, int $limit, string $direction): array
    {
        $mailbox = $this->openMailbox($folder);
        $uids = imap_search($mailbox, $criteria, SE_UID);
        if (! is_array($uids)) {
            imap_close($mailbox);

            return [];
        }

        $messages = [];
        $uids = array_reverse($uids);
        foreach (array_slice($uids, 0, max(1, $limit)) as $uid) {
            if (! is_int($uid) && ! is_string($uid)) {
                continue;
            }

            $messages[] = $this->messagePayload($mailbox, (string) $uid, $direction, $folder);
        }

        imap_close($mailbox);

        return $messages;
    }

    /**
     * @return array<string, mixed>
     */
    private function mailboxStats(string $folder): array
    {
        $mailbox = $this->openMailbox($folder);
        $check = imap_check($mailbox);
        imap_close($mailbox);

        return [
            'folder' => $folder,
            'messages' => isset($check->Nmsgs) ? (int) $check->Nmsgs : null,
        ];
    }

    private function openMailbox(string $folder): Connection
    {
        if (! function_exists('imap_open')) {
            throw new \RuntimeException('Estensione PHP imap non installata: impossibile leggere la inbox.');
        }

        if ($this->settings->imapHost() === '' || $this->settings->imapUsername() === '' || $this->settings->imapPassword() === '') {
            throw new \RuntimeException('Configurazione IMAP email incompleta.');
        }

        try {
            return imap_open(
                $this->mailboxString($folder),
                $this->settings->imapUsername(),
                $this->settings->imapPassword(),
            );
        } catch (ImapException $imapException) {
            throw new \RuntimeException('Connessione IMAP fallita: '.$imapException->getMessage(), 0, $imapException);
        }
    }

    private function mailboxString(string $folder): string
    {
        $flags = match ($this->settings->imapEncryption()) {
            'tls' => '/imap/tls',
            'none' => '/imap/notls',
            default => '/imap/ssl',
        };

        return sprintf(
            '{%s:%d%s}%s',
            $this->settings->imapHost(),
            $this->settings->imapPort(),
            $flags,
            $folder,
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function messagePayload(Connection $mailbox, string $uid, string $direction, string $folder): array
    {
        $overviewItems = imap_fetch_overview($mailbox, $uid, FT_UID);
        $overview = isset($overviewItems[0]) && is_object($overviewItems[0])
            ? $overviewItems[0]
            : new \stdClass();

        $headers = imap_rfc822_parse_headers(imap_fetchheader($mailbox, (int) $uid, FT_UID | FT_PEEK));
        $from = is_array($headers->from ?? null) ? ($headers->from[0] ?? null) : null;
        $to = is_array($headers->to ?? null) ? ($headers->to[0] ?? null) : null;

        return [
            'uid' => $uid,
            'message_id' => $this->messageId($overview->message_id ?? null, $direction, $uid),
            'direction' => $direction,
            'from_email' => $this->addressEmail($from),
            'from_name' => $this->addressName($from),
            'to_email' => $this->addressEmail($to),
            'subject' => $this->decodeHeader($this->headerString($overview->subject ?? null)),
            'text' => trim($this->body($mailbox, $uid)),
            'received_at' => $this->headerString($overview->date ?? null),
            'source' => 'imap',
            'folder' => $folder,
        ];
    }

    private function messageId(mixed $headerValue, string $direction, string $uid): string
    {
        $header = $this->headerString($headerValue);

        return $header !== '' ? $direction.':'.$header : $direction.':imap-'.$uid;
    }

    private function body(Connection $mailbox, string $uid): string
    {
        $body = imap_fetchbody($mailbox, (int) $uid, '1', FT_UID | FT_PEEK);
        if (trim($body) === '') {
            $body = imap_body($mailbox, (int) $uid, FT_UID | FT_PEEK);
        }

        return quoted_printable_decode($body);
    }

    private function addressEmail(mixed $address): string
    {
        if (! is_object($address)) {
            return '';
        }

        $mailbox = is_string($address->mailbox ?? null) ? $address->mailbox : '';
        $host = is_string($address->host ?? null) ? $address->host : '';

        return $mailbox !== '' && $host !== '' ? strtolower($mailbox.'@'.$host) : '';
    }

    private function addressName(mixed $address): string
    {
        if (! is_object($address) || ! is_string($address->personal ?? null)) {
            return '';
        }

        return $this->decodeHeader($address->personal);
    }

    private function decodeHeader(string $value): string
    {
        return function_exists('imap_utf8') ? trim(imap_utf8($value)) : trim($value);
    }

    private function headerString(mixed $value): string
    {
        return is_string($value) ? trim($value) : '';
    }
}
