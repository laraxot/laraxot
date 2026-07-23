<?php

declare(strict_types=1);

return [
    'login' => [
        'title' => 'Service Access',
        'description' => 'Enter your credentials to access :service',
        'no_account' => 'Don\'t have an account?',
        'create_account' => 'Register',
        'security_title' => 'Secure Connection',
        'security_message' => 'Your data is protected with SSL encryption',
        'accessibility_info' => 'This service is accessible according to WCAG 2.1 AA guidelines.',
        'accessibility_declaration' => 'Accessibility Declaration',
        'keyboard_navigation' => 'Use Tab to navigate between fields and Enter to submit the form.',
        'or' => 'or',
        'email' => 'Email address',
        'password' => 'Password',
        'remember_me' => 'Remember me',
        'forgot_password' => 'Forgot your password?',
        'submit' => 'Login',
        'help' => 'Need help?',
    ],
    'register' => [
        'title' => 'Registration',
        'description' => 'Create your account to access services',
        'name' => 'Full name',
        'email' => 'Email address',
        'password' => 'Password',
        'password_confirmation' => 'Confirm password',
        'submit' => 'Register',
        'already_registered' => 'Already have an account?',
        'login' => 'Login',
    ],
    'failed' => 'These credentials do not match our records.',
    'password' => 'The provided password is incorrect.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
    'general_error' => 'An error occurred. Please try again later.',
    'unauthorized' => 'You do not have the necessary permissions for this operation.',

    // Logout
    'logout' => [
        'title' => 'Logout',
        'confirm_message' => 'Are you sure you want to logout?',
        'confirm_button' => 'Confirm Logout',
        'cancel_button' => 'Cancel',
        'success_title' => 'Logout successful',
        'success_message' => 'You have been successfully logged out.',
        'error_title' => 'Logout error',
        'error_message' => 'An error occurred during logout.',
        'try_again' => 'Try again',
        'back_to_home' => 'Back to Home',
    ],

    // Profile
    'profile' => [
        'title' => 'Profile',
        'settings' => 'Settings',
        'information' => 'Profile Information',
        'update_password' => 'Update Password',
        'current_password' => 'Current Password',
        'new_password' => 'New Password',
        'confirm_password' => 'Confirm Password',
        'save' => 'Save',
        'update' => 'Update',
    ],

    // Reset Password
    'reset' => [
        'title' => 'Reset Password',
        'email' => 'Email',
        'password' => 'New Password',
        'password_confirmation' => 'Confirm Password',
        'submit' => 'Reset Password',
        'link_sent' => 'We have emailed your password reset link!',
        'reset_link' => 'Reset Password',
    ],

    // Verify Email
    'verify' => [
        'title' => 'Verify Email',
        'message' => 'Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?',
        'resend' => 'If you didn\'t receive the email, we will gladly send you another.',
        'submit' => 'Resend verification email',
    ],

    // Navigation
    'navigation' => [
        'open_menu' => 'Open main menu',
        'close_menu' => 'Close main menu',
        'home' => 'Home',
        'dashboard' => 'Dashboard',
        'profile' => 'Profile',
        'settings' => 'Settings',
    ],

    // User dropdown
    'user_dropdown' => [
        'manage_account' => 'Manage Account',
        'profile' => 'Profile',
        'settings' => 'Settings',
        'logout' => 'Logout',
        'login_link' => 'Login',
        'register_link' => 'Register',
    ],

    // Notification messages
    'notifications' => [
        'login_success' => 'Login successful',
        'login_error' => 'Login error',
        'logout_success' => 'Logout successful',
        'logout_error' => 'Logout error',
        'validation_error' => 'Validation error',
        'general_error' => 'An error occurred. Please try again later.',
    ],
];
