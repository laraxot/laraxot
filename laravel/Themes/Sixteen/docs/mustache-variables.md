# Mustache Variables for Sixteen Theme Mail Layouts

The following variables are available in the Sixteen theme email layout templates. They are rendered using Mustache syntax (`{{ variable }}`) and can be passed from a Laravel Mailable or from the `SendRecordNotificationAction`.

| Variable | Description | Example Value |
|----------|-------------|---------------|
| `{{ logo_header }}` | URL to the header logo image. If not provided, the company name is displayed as a heading. | `https://example.com/logo.png` |
| `{{ company_name }}` | The name of the company/brand. Used throughout the email (header, signature, footer). | `Sottana Service` |
| `{{ company_address }}` | Physical address of the company, displayed in the footer. | `Via Roma 1, 00100 Roma, Italia` |
| `{{ site_url }}` | Link to the company's website. | `https://sottanaservice.it` |
| `{{ unsubscribe_url }}` | URL for the unsubscribe link (required for compliance). | `https://sottanaservice.it/unsubscribe?token=abc123` |
| `{{ year }}` | Current year, typically injected automatically. | `2025` |
| `{{ body }}` | Main content of the email. This is the dynamic part supplied by the specific notification. | `<p>Grazie per aver scelto i nostri servizi...</p>` |
| `{{ action_url }}` | URL for the primary call‑to‑action button (e.g., link to the user portal). | `https://sottanaservice.it/portal` |
| `{{ action_text }}` | Text displayed on the CTA button. If omitted, defaults to "Area Riservata". | `Accedi al Portale` |

**Usage Example (Blade/Mustache):**
```html
{{#logo_header}}
    <img src="{{ logo_header }}" alt="{{ company_name }}" class="email-logo">
{{/logo_header}}
{{^logo_header}}
    <h2 style="color: #800000; font-size: 24px;">{{ company_name }}</h2>
{{/logo_header}}

{{{ body }}}

{{#action_url}}
    <a href="{{ action_url }}" class="btn">{{#action_text}}{{ action_text }}{{/action_text}}{{^action_text}}Area Riservata{{/action_text}}</a>
{{/action_url}}
```
