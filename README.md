# MiHRM - Your Complete HR Management Solution 🚀

![Laravel](https://img.shields.io/badge/Laravel-9.x-orange) ![PHP](https://img.shields.io/badge/PHP-^8.0-blue) ![License](https://img.shields.io/badge/License-MIT-success) ![Build Status](https://img.shields.io/badge/build-passing-brightgreen)

**MiHRM** is a modern, Laravel-based HR Management System designed to streamline your HR processes, offering tools to manage everything from employee attendance to salary management, project assignments, perks, and more — all in one place!

---

## 🌟 Features at a Glance
- **Employee Attendance**: Monitor attendance and manage working hours.
- **Leave Requests**: Simple and intuitive leave request handling.
- **Project Management**: Easily create, update, and track projects.
- **Project Assignment**: Seamless assignment of projects to employees.
- **Salary Management**: Accurate and efficient salary tracking.
- **Perks & Benefits**: Manage employee perks with ease.
- **Two-Factor Authentication (2FA)**: Secure your system with 2FA.
- **Admin Controls**: Powerful tools for admins to manage HR workflows.
- **Announcements**: Publish and manage company-wide announcements.

---

## 📋 Table of Contents
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Core Functionalities](#core-functionalities)
  - [Employee Attendance](#employee-attendance)
  - [Leave Requests](#leave-requests)
  - [Project Management](#project-management)
  - [Project Assignment](#project-assignment)
  - [Salary Management](#salary-management)
  - [Perks Management](#perks-management)
  - [Two-Factor Authentication (2FA)](#two-factor-authentication-2fa)
  - [Announcements](#announcements)
- [Packages](#packages)
- [Queue](#queue)
- [Cron Jobs](#cron-jobs)
- [Events](#events)
- [Middlewares](#middlewares)
- [Contributing](#contributing)
- [License](#license)

---

## ⚡️ Quick Start: Installation

Ready to get started? Follow these steps to set up MiHRM on your local environment.

1. **Clone the repository:**
    ```shell
    git clone https://github.com/SSTalha/MiHRM
    cd MiHRM/
    ```

2. **Install dependencies:**
    ```shell
    composer install
    ```

3. **Set up environment variables:**
    ```shell
    cp .env.example .env
    php artisan key:generate
    ```

4. **Configure your database in the `.env` file:**
    ```plaintext
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    ```

5. **Run migrations to set up the database schema:**
    ```shell
    php artisan migrate
    ```

6. **Run database seeders:**
    ```shell
    php artisan db:seed
    ```

7. **Generate JWT secret key:**
    ```shell
    php artisan jwt:secret
    ```

---

## ⚙️ Configuration

MiHRM is highly customizable. Tailor it to your needs by configuring:
- **Cache** settings
- **Database** connection
- **Email** notifications
- **Queue** drivers

All configurations can be adjusted in the `.env` file for flexibility in various environments.

### Mail Configuration

Configure your mail settings in the `.env` file to enable email notifications and other mail features:
```plaintext
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

MiHRM supports various mail services to send notifications, alerts, and other important communications. Make sure to set up your mail service provider accordingly.

---

## 🚀 Usage

Once installed, start the development server with:
```shell
php artisan serve
```

Then, open your browser and visit: `http://localhost:8000` to start managing your HR tasks effortlessly.

---

## 💡 Core Functionalities

### 1. Employee Attendance
Track employee attendance and working hours effortlessly. MiHRM’s attendance system enables you to monitor productivity, ensure compliance with work schedules, and maintain accurate attendance records. Employees can log their hours, and managers can review attendance reports to ensure all employees are adhering to their work schedules.

### 2. Leave Requests
Employees can submit leave requests that follow a structured approval workflow, helping HR manage leave applications efficiently. This feature maintains a record of leave balances, making it simple for both employees and managers to track remaining leave days. Managers can approve or reject requests and provide feedback directly through the system.

### 3. Project Management
Easily create, update, and delete projects. This functionality ensures that all projects are properly documented and tracked throughout their lifecycle. Administrators can assign team members to projects, set deadlines, and add necessary resources. Track project progress, manage timelines, and ensure milestones are met, all in one place.

### 4. Project Assignment
Assign projects to employees with ease. MiHRM allows administrators to validate employee IDs and assign projects accordingly. This ensures accurate tracking of project responsibilities, and employees can view their assignments and deadlines. Seamlessly reassign projects and manage workloads to ensure balanced distribution of tasks.

### 5. Salary Management
Manage payroll with precision. This module ensures all salary records are up to date and processed correctly. Admins can update salary details, issue payments, and track salary histories. Generate comprehensive payroll reports, manage deductions and increments, and ensure timely salary disbursement.

### 6. Perks Management
Reward and incentivize your employees by managing perks and benefits efficiently. The system allows you to set up various perks, such as bonuses, allowances, and benefits. Track the assignment of these perks to employees, and automatically calculate eligibility based on predefined criteria. Ensure your employees feel valued and motivated.

### 7. Two-Factor Authentication (2FA)
Security is a priority! MiHRM integrates with Google Authenticator to provide 2FA, giving your employees and admins an extra layer of security when logging in. This feature ensures that even if login credentials are compromised, unauthorized access is prevented. Protect sensitive data and maintain high security standards.

### 8. Announcements
Publish and manage company-wide announcements efficiently. This feature allows admins to create, schedule, and publish announcements visible to all employees. Use this tool to inform staff about important updates, upcoming events, policy changes, and more. Keep everyone in the loop with timely and well-documented announcements.

---

## 📦 Packages Used

MiHRM leverages powerful third-party packages to extend functionality. Some of the key packages include: 
- `guzzlehttp/guzzle - laravel/tinker`
- `spatie/laravel-permission`
- `tymon/jwt-auth`
- `pragmarx/google2fa`
- `laravel`
- `simplesoftwareio/simple-qrcode`

And many more!

---

## 🛠️ Queue

MiHRM utilizes a database queue connection for managing background tasks efficiently. You can configure the queue driver in the `.env` file:
```plaintext
QUEUE_CONNECTION=database
```

To process the queued jobs, run the following command:
```shell
php artisan queue:work
```

---

## ⏲️ Cron Jobs

MiHRM schedules various cron jobs to automate tasks. To ensure these tasks run as scheduled, set up a cron job on your server to run the Laravel command scheduler:

1. Open the crontab:
    ```shell
    crontab -e
    ```

2. Add the following line to run the Laravel scheduler every minute:
    ```plaintext
    * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
    ```

### Scheduled Commands

MiHRM includes the following scheduled commands:

- **Update Attendance Record:**
    ```shell
    php artisan attendance:update-record
    ```

- **Add Unpaid Salary:**
    ```shell
    php artisan salary:add-unpaid
    ```

- **Handle Leave Requests:**
    ```shell
    php artisan leave:handle
    ```

- **Pay Salaries:**
    ```shell
    php artisan salary:pay
    ```

- **Publish Announcements:**
    ```shell
    php artisan announcements:publish
    ```

- **Custom DTO Generation:**
    ```shell
    php artisan make:dto
    ```

- **Custom Helper Generation:**
    ```shell
    php artisan make:helper
    ```

- **Custom Service Generation:**
    ```shell
    php artisan make:service
    ```

---

## 🎉 Contributing

We love contributions! Follow these steps to contribute:
1. Fork the repo.
2. Create a new branch with your feature/fix.
3. Push your changes and open a Pull Request.

Feel free to check out the issues section for features you could help with!

---

## 📄 License

MiHRM is licensed under the [MIT License](LICENSE), making it open and free for both personal and commercial use.

---

💬 **Questions?** Reach out by opening an issue or contributing to the discussion!

Made with ❤️ by MiHRM contributors.
