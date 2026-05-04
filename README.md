# GBVAid

**GBVAid** is a secure, comprehensive digital gateway connecting individuals to essential medical, legal, and counseling services for Gender-Based Violence (GBV) support across Africa. The platform is designed to empower survivors, advocates, and communities through technology, ensuring confidentiality, reliable support, and access to crucial resources.

## 🌟 Key Features

*   **Access to Services:** Find verified healthcare, legal, and counseling support in your vicinity.
*   **Safe Reporting:** Secure and anonymous reporting of GBV incidents, connecting directly to trusted responder partners.
*   **Community Empowerment:** Forums, groups, and advocacy campaigns for survivor-led social change.
*   **AI Integration:** Includes an *AI Safety Room* to talk to an AI listener without judgment, providing immediate comfort and triage.
*   **Service Provider Portals:** Specialized dashboards for support staff, admins, and medical professionals to manage cases, triage incidents, and schedule consultations.
*   **Data Privacy:** Secure encrypted sessions and secure vault storage for uploading sensitive evidence.

## 🛠️ Technology Stack

*   **Backend:** PHP (Vanilla)
*   **Database:** MySQL
*   **Frontend:** HTML5, Bootstrap 5, Vanilla JavaScript, CSS3
*   **APIs:** Custom AI proxy integration for chat support

## 🚀 Getting Started

### Prerequisites

*   PHP >= 8.0
*   MySQL/MariaDB Database Server
*   Web Server (e.g., Apache/XAMPP, Nginx)

### Installation

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/AmannaP/Capstone-GBVAid.git
    cd Capstone-GBVAid
    ```

2.  **Configure the Database:**
    *   Create a new MySQL database named `capstone`.
    *   Import the SQL schema located at `db/capstone.sql`.
    *   Update the database credentials in `settings/db_cred.php`.

3.  **Environment Setup:**
    *   If using XAMPP/local environment, ensure curl and openssl extensions are enabled in your `php.ini` for the AI proxy and external API calls to function correctly.

4.  **Run the Application:**
    *   Start your web server.
    *   Navigate to the project root in your browser (e.g., `http://localhost/Capstone-GBVAid`).

## 📁 Directory Structure

*   `/actions`: PHP scripts handling form submissions and core logic (e.g., booking, AI proxy, forgot password).
*   `/admin`: Dashboard and views for administrative oversight.
*   `/classes`: Core PHP classes representing the data models (User, Appointment, Evidence, etc.).
*   `/controllers`: Functions bridging the frontend requests with the backend classes.
*   `/db`: Database schema and patch files.
*   `/sp`: Service Provider (Doctor, Counselor, Legal) dashboard and views.
*   `/user`: End-user dashboard, chat, and service interaction pages.
*   `/js`: JavaScript logic handling asynchronous requests and interactive UI elements.
*   `/uploads`: Directory for secure evidence and user profile image storage.

## 🛡️ Security Note

All interactions, especially evidence uploads and chat histories, handle sensitive data. Ensure the production environment is secured with SSL/TLS and proper directory permissions are enforced on the `/uploads` and `/sessions` directories.

## 📄 License

© GBVAid | All Rights Reserved. Built to empower GBV survivors and communities through technology.
