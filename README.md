# Final-E-commerce-Platform
GBVAid is a web-based platform that enables users to access gender-based violence resources, report incidents securely, and connect with verified support services. It includes services listings, sessions booking/checkout features, admin management tools, and integrated Paystack payments.


# GBVAid - Gender-Based Violence Support Platform 💜

![Project Status](https://img.shields.io/badge/Status-Active-success)
![PHP](https://img.shields.io/badge/PHP-8.0%2B-777bb4)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479a1)
![Bootstrap](https://img.shields.io/badge/Frontend-Bootstrap%205-7952b3)

**GBVAid** is a comprehensive web-based support platform designed to provide a safe haven for victims and survivors of Gender-Based Violence (GBV). It moves beyond simple reporting to offer a holistic ecosystem of support, including professional appointment booking, community chat groups, and educational resources.

---

## 📖 Table of Contents
- [About the Project](#-about-the-project)
- [Key Features](#-key-features)
- [System Architecture](#-system-architecture)
- [Technology Stack](#-technology-stack)
- [Database Structure](#-database-structure)
- [Installation & Setup](#-installation--setup)
- [Usage Guide](#-usage-guide)
- [Screenshots](#-screenshots)
- [Future Improvements](#-future-improvements)
- [License](#-license)

---

## 💡 About the Project
Gender-Based Violence is a global crisis, and victims often lack a centralized, secure way to seek help. **GBVAid** bridges this gap by connecting survivors with verified service providers (Counselors, Legal Aid, Medical Support) while ensuring anonymity and security.

Unlike standard e-commerce sites, GBVAid handles **Service Bookings** rather than physical products, prioritizing mental health and safety.

---

## ✨ Key Features

### 🛡️ For Users (Survivors)
* **Confidential Reporting:** Submit incident reports securely (with an option for anonymity) to be reviewed by admins/authorities.
* **Service Booking:** Schedule appointments with psychologists, doctors, or legal advisors.
    * *Includes Date/Time selection and Note submission.*
* **Community Chat:** Real-time support groups (Domestic Violence, General Support) using AJAX long-polling for instant communication.
* **Safety Resources:** Access emergency hotlines, safety plans, and legal rights information.
* **Secure Payments:** Integrated **Paystack** gateway for paid consultation sessions.
* **Dashboard:** A centralized hub to manage reports, appointments, and profile settings.

### 🔐 For Admins
* **Service Management:** CRUD operations for Service Categories (Brands) and Services (Products).
* **Content Management:** Post and manage awareness/educational content dynamically.
* **Report Management:** Review incident reports and update status (Pending -> Investigating -> Resolved).
* **Analytics:** View total users, active bookings, and platform activity.

---

## 🏗 System Architecture
This project is built using a custom **Model-View-Controller (MVC)** architecture to ensure code modularity and security.

* **Actions:** Handle form submissions and AJAX requests.
* **Controllers:** Act as the bridge, processing logic, and calling the classes.
* **Classes (Models):** Handle direct database interactions (PDO).
* **Views:** The frontend user interface.
* **Settings:** Database connection and core configurations.

---

## 🛠 Technology Stack

* **Frontend:** HTML5, CSS3, Bootstrap 5, JavaScript (jQuery), SweetAlert2.
* **Backend:** PHP (Native/Vanilla).
* **Database:** MySQL (Relational).
* **Payment Gateway:** Paystack API.
* **Icons:** Bootstrap Icons.

---

## 🗄 Database Structure
The system relies on the following key tables:
* `customer`: User data and credentials.
* `products`: Stores services available for booking.
* `cart`: Temporary holding for booking sessions before payment.
* `orders` & `orderdetails`: Financial records of confirmed bookings.
* `appointments`: Tracks scheduled dates, times, and status.
* `reports`: Stores incident details submitted by users.
* `chat_groups` & `chat_messages`: Handles community discussions.
* `awareness`: Stores educational content.

---

## 🚀 Installation & Setup

### Prerequisites
* XAMPP/WAMP/MAMP (Apache & MySQL).
* A Paystack Developer Account (Public/Secret Keys).

### Steps
1.  **Clone the Repository**
    ```bash
    git clone https://github.com/AmannaP/Final-E-commerce-Platform.git
    ```
2.  **Move to HTDOCS**
    Copy the project folder into your `htdocs` (XAMPP) or `www` (WAMP) directory.

3.  **Import Database**
    * Open **phpMyAdmin**.
    * Create a database named `dbforlab` (or update `db_cred.php`).
    * Import the `database/dbforlab.sql` file included in this repo.

4.  **Configure Database Connection**
    * Navigate to `settings/db_cred.php`.
    * Update `DB_SERVER`, `DB_USERNAME`, `DB_PASSWORD`, and `DB_NAME`.

5.  **Configure Paystack**
    * Navigate to `settings/paystack_config.php`.
    * Enter your **Paystack Public Key** and **Secret Key**.

6.  **Run the Application**
    * Open your browser and visit: `http://localhost/GBVAid/index.php`

---

## 📖 Usage Guide

### Guest Users
* Can browse services and view safety resources.
* Must register/login to book appointments or join chat rooms.

### Registered Users
* **Login** to access the Dashboard.
* Use the **Chat** feature to talk in support groups.
* **Book** a session by selecting a service, choosing a date/time, and paying via Paystack.
* View **My Appointments** to check status (Confirmed/Pending).

### Admin Access
* Login with Admin credentials.
* Navigate to `admin/dashboard.php` to manage the platform.

---

## 📸 Snapshot


| User Dashboard | Booking Page |
|:---:|:---:|
| ![Dashboard](images/dashboard.png) | ![Booking](images/booking.png) |

| Chat Room | Admin Panel |
|:---:|:---:|
| ![Chat](images/chat.png) | ![Admin](images/admin.png) |

---

## 🔮 Future Improvements
* **Video Integration:** Real-time video counseling sessions using WebRTC.
* **Geolocation:** Map integration to find the nearest physical shelters or police stations.
* **SMS Alerts:** Twilio integration to send SMS notifications for appointment reminders.

---

## 📝 License
This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

**Built with 💜 to support and empower Gender-based violence victims.**
