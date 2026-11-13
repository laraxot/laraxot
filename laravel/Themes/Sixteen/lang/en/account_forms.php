<?php

declare(strict_types=1);

return [
    'profile' => [
        'page_title' => [
            'title' => 'Profile',
        ],
        'information' => [
            'title' => 'Profile Information',
            'help' => 'Update your account\'s profile information and email address.',
        ],
        'photo' => [
            'label' => 'Photo',
        ],
        'photo_select' => [
            'label' => 'Select A New Photo',
        ],
        'photo_remove' => [
            'label' => 'Remove Photo',
        ],
        'email_unverified' => [
            'message' => 'Your email address is unverified.',
        ],
        'email_resend' => [
            'label' => 'Click here to re-send the verification email.',
        ],
        'email_resent' => [
            'message' => 'A new verification link has been sent to your email address.',
        ],
    ],
    'password' => [
        'update' => [
            'title' => 'Update Password',
            'help' => 'Ensure your account is using a long, random password to stay secure.',
        ],
        'current' => [
            'label' => 'Current Password',
        ],
        'new' => [
            'label' => 'New Password',
        ],
        'confirm' => [
            'label' => 'Confirm Password',
        ],
    ],
    'common' => [
        'password' => [
            'label' => 'Password',
            'placeholder' => 'Password',
        ],
        'cancel' => [
            'label' => 'Cancel',
        ],
        'save' => [
            'label' => 'Save',
        ],
        'saved' => [
            'message' => 'Saved.',
        ],
        'done' => [
            'message' => 'Done.',
        ],
        'confirm' => [
            'label' => 'Confirm',
        ],
        'create' => [
            'label' => 'Create',
        ],
        'email' => [
            'label' => 'Email',
        ],
        'name' => [
            'label' => 'Name',
        ],
        'update' => [
            'label' => 'Update',
        ],
    ],
    'delete_account' => [
        'action' => [
            'label' => 'Delete Account',
        ],
        'summary' => [
            'help' => 'Permanently delete your account.',
        ],
        'warning' => [
            'help' => 'Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.',
        ],
        'confirm' => [
            'title' => 'Are you sure you want to delete your account?',
        ],
        'confirm_full' => [
            'message' => 'Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.',
        ],
        'confirm_password' => [
            'message' => 'Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.',
        ],
        'legacy_warning' => [
            'help' => 'After deleting your account, all data and resources are permanently removed. Enter your password to confirm deletion.',
        ],
    ],
    'browser_sessions' => [
        'list' => [
            'title' => 'Browser Sessions',
            'help' => 'Manage and log out your active sessions on other browsers and devices.',
        ],
        'description' => [
            'help' => 'If necessary, you may log out of all of your other browser sessions across all of your devices. Some of your recent sessions are listed below; however, this list may not be exhaustive. If you feel your account has been compromised, you should also update your password.',
        ],
        'unknown' => [
            'label' => 'Unknown',
        ],
        'this_device' => [
            'label' => 'This device',
        ],
        'last_active' => [
            'label' => 'Last active',
        ],
        'log_out_others' => [
            'label' => 'Log Out Other Browser Sessions',
        ],
        'confirm' => [
            'message' => 'Please enter your password to confirm you would like to log out of your other browser sessions across all of your devices.',
        ],
    ],
    'two_factor' => [
        'setup' => [
            'title' => 'Two Factor Authentication',
            'help' => 'Add additional security to your account using two factor authentication.',
        ],
        'state_finish' => [
            'title' => 'Finish enabling two factor authentication.',
        ],
        'state_enabled' => [
            'title' => 'You have enabled two factor authentication.',
        ],
        'state_disabled' => [
            'title' => 'You have not enabled two factor authentication.',
        ],
        'intro' => [
            'help' => 'When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone\'s Google Authenticator application.',
        ],
        'scan_finish' => [
            'help' => 'To finish enabling two factor authentication, scan the following QR code using your phone\'s authenticator application or enter the setup key and provide the generated OTP code.',
        ],
        'scan_enabled' => [
            'help' => 'Two factor authentication is now enabled. Scan the following QR code using your phone\'s authenticator application or enter the setup key.',
        ],
        'setup_key' => [
            'label' => 'Setup Key',
        ],
        'code' => [
            'label' => 'Code',
        ],
        'recovery_codes' => [
            'help' => 'Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.',
        ],
        'enable' => [
            'label' => 'Enable',
        ],
        'recovery_regenerate' => [
            'label' => 'Regenerate Recovery Codes',
        ],
        'recovery_show' => [
            'label' => 'Show Recovery Codes',
        ],
        'disable' => [
            'label' => 'Disable',
        ],
    ],
    'team' => [
        'settings' => [
            'title' => 'Team Settings',
        ],
        'create' => [
            'title' => 'Create Team',
            'help' => 'Create a new team to collaborate with others on projects.',
        ],
        'details' => [
            'title' => 'Team Details',
        ],
        'owner' => [
            'label' => 'Team Owner',
        ],
        'name' => [
            'label' => 'Team Name',
            'help' => 'The team\'s name and owner information.',
        ],
        'delete' => [
            'label' => 'Delete Team',
        ],
        'delete_summary' => [
            'help' => 'Permanently delete this team.',
        ],
        'delete_warning' => [
            'help' => 'Once a team is deleted, all of its resources and data will be permanently deleted. Before deleting this team, please download any data or information regarding this team that you wish to retain.',
        ],
        'delete_confirm' => [
            'message' => 'Are you sure you want to delete this team? Once a team is deleted, all of its resources and data will be permanently deleted.',
        ],
    ],
    'members' => [
        'add' => [
            'title' => 'Add Team Member',
            'help' => 'Add a new team member to your team, allowing them to collaborate with you.',
        ],
        'add_email' => [
            'help' => 'Please provide the email address of the person you would like to add to this team.',
        ],
        'role' => [
            'label' => 'Role',
        ],
        'added' => [
            'message' => 'Added.',
        ],
        'add_action' => [
            'label' => 'Add',
        ],
        'pending' => [
            'title' => 'Pending Team Invitations',
            'help' => 'These people have been invited to your team and have been sent an invitation email. They may join the team by accepting the email invitation.',
        ],
        'list' => [
            'title' => 'Team Members',
            'help' => 'All of the people that are part of this team.',
        ],
        'leave' => [
            'label' => 'Leave',
        ],
        'remove' => [
            'label' => 'Remove',
        ],
        'manage_role' => [
            'title' => 'Manage Role',
        ],
        'leave_team' => [
            'title' => 'Leave Team',
            'message' => 'Are you sure you would like to leave this team?',
        ],
        'remove_member' => [
            'title' => 'Remove Team Member',
            'message' => 'Are you sure you would like to remove this person from the team?',
        ],
    ],
];
