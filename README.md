<html>
  <h3 >Zeph here👋</h3>
  <p>This Repository Contains my Mini-Project "SpareHub"</p>
  Feel Free to Refer my Project👍
</html>

### **Core Features**

#### **1. Frontend (Client-Side)**

* **Role-Based UI & Navigation:** Developed a modular interface using **HTML5**, **CSS3**, and **JavaScript** that features customized dashboards for **Admins**, **Buyers**, and **Sellers**, ensuring role-specific access to data and actions.
* **Interactive Catalog & State Management:** Engineered a dynamic search system to filter automotive parts by brand, model, and year, while leveraging **LocalStorage** to maintain persistent shopping cart data across browser sessions.

#### **2. Backend (Server-Side)**

* **Secure Authentication & Access Control:** Implemented **PHP session management** and **MySQLi prepared statements** to secure user data and facilitate automated routing based on **User ID prefixes** (e.g., 100s for Admin, 200s for Buyers, 300s for Sellers).
* **Business Logic & Workflow Automation:** Managed complex e-commerce workflows, including a multi-stage **seller verification system** with credential uploads and administrative review, alongside comprehensive **order lifecycle tracking** from pending to delivered.

#### **3. Database (Data Layer)**

* **Relational Schema & Data Integrity:** Designed a normalized **MySQL database** with interconnected tables (Users, Parts, Orders, etc.) using **Foreign Key constraints** to ensure consistent relationships between user roles and inventory.
* **Inventory & Audit Management:** Optimized data storage for automotive metadata, including **OEM numbers** and part conditions, while integrating automated **timestamps** to maintain an audit trail of inventory updates and administrative actions.

---

### **Tech Stack**

* **Frontend:** HTML5, CSS3, JavaScript (ES6+).
* **Backend:** PHP (8.2).
* **Database:** MariaDB / MySQL.
* **Server:** Apache (XAMPP/WAMP environment).

---

### **Installation**

1. **Clone the repository:** `git clone https://github.com/your-username/sparehub.git`
2. **Database Setup:** Import the `sparehub.sql` file located in the `database/` folder into your local MySQL server.
3. **Configuration:** Update the database credentials in `database/db.php` if necessary.
4. **Run:** Move the project folder to your local server directory (e.g., `htdocs`) and access it via `localhost/SpareHub/pages/Homepage.php`.
