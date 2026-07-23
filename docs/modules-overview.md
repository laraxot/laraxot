# Modules Overview

**Last Updated**: 2025-12-05
**Status**: High-Level Summary

This document provides a high-level overview of the purpose of each module within the project. For detailed documentation, refer to the `docs` directory inside each module's folder.

---

### Foundation Modules

-   **Xot**: The foundational "shared kernel" module. It provides the core architectural patterns, base classes (Models, Resources, Services), traits, and shared functionalities that all other modules depend on. It is the bedrock of the entire application.
-   **Lang**: Provides the core multilingual capabilities, including base resources and classes for handling translations.
-   **UI**: Contains shared User Interface components, Blade views, and theming elements used across different modules to maintain a consistent look and feel.

### Core Business & Application Modules

-   **TechPlanner**: The primary business logic module. It contains the specific functionality for the TechPlanner application, including management of clients, appointments, technical devices, and professional contacts.
-   **Tenant**: Manages the multi-tenancy architecture. It handles tenant registration, domain mapping, and data isolation, allowing the platform to serve multiple clients from a single instance.
-   **User**: Responsible for all aspects of user management, including authentication, authorization (roles and permissions), user profiles, and team management.
-   **Employee**: Manages employee-specific data, roles, and relationships within the application.
-   **Cms**: A Content Management System. Used for creating and managing static pages, articles, or other content-driven parts of the application.

### Supporting & Utility Modules

-   **Activity**: Handles activity logging and creates audit trails for user and system actions.
-   **Chart**: Provides charting and data visualization components, likely integrated with Filament.
-   **Gdpr**: Implements features required for GDPR compliance, such as personal data export, anonymization, and consent management.
-   **Geo**: Manages geographical data. This includes address storage, location services, and potentially geo-fencing or mapping functionalities.
-   **Job**: Likely manages and monitors background jobs and queued tasks within the application.
-   **Media**: Provides a centralized media library for managing file uploads, such as images, documents, and other assets.
-   **Notify**: Manages the sending of notifications through various channels like email, SMS, or in-app alerts.
