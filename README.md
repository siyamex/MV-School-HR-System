# 🏫 HR School Management System

A comprehensive Human Resource Management System designed specifically for educational institutions. This web-based application helps schools manage their staff, departments, attendance, leaves, overtime, and payroll efficiently.

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)

## 🚀 Features

### 👥 User Management
- **Multi-role System**: Super Admin, Admin, Supervisor, and Staff roles
- **Google OAuth Integration**: Seamless login with Google accounts
- **User Profile Management**: Complete profile management with avatar upload
- **Email Notifications**: Automated email notifications for various events

### 🏢 Department Management
- **Department Creation**: Create and manage school departments
- **Department Heads**: Assign supervisors as department heads
- **Employee Assignment**: Assign employees to departments
- **Department Statistics**: View employee count and department analytics

### 👨‍🏫 Employee Management
- **Complete Employee Records**: Manage comprehensive employee information
- **Employee ID Generation**: Auto-generated unique employee IDs
- **Bulk Import/Export**: Excel import/export functionality
- **Employee Search & Filter**: Advanced search and filtering options
- **Employee Profiles**: Detailed employee profiles with contact information

### 📅 Attendance Management
- **ZKTeco Integration**: Connect with ZKTeco biometric devices
- **Manual Attendance**: Manual attendance entry for special cases
- **Attendance Reports**: Comprehensive attendance reporting
- **Real-time Sync**: Automatic synchronization with biometric devices
- **Export Options**: Export attendance data in various formats

### 🏖️ Leave Management
- **Leave Types**: Multiple leave types (sick, annual, emergency, etc.)
- **Leave Applications**: Employee self-service leave applications
- **Approval Workflow**: Multi-level approval system
- **Leave Balance**: Track leave balances and entitlements
- **Email Notifications**: Automated notifications for leave status

### ⏰ Overtime Management
- **Overtime Requests**: Employee overtime request submission
- **Approval System**: Supervisor and admin approval workflow
- **Overtime Reports**: Detailed overtime tracking and reports
- **Automatic Calculations**: Calculate overtime hours and compensation

### 💰 Salary Management
- **Salary Slip Generation**: Generate monthly salary slips
- **Bulk Upload**: Upload salary data via Excel files
- **PDF Generation**: Generate PDF salary slips
- **Salary Templates**: Customizable salary slip templates
- **Download Options**: Download individual or bulk salary slips

### ⚙️ System Settings
- **Application Configuration**: Configure app name, logo, and settings
- **Email Templates**: Customize notification email templates
- **SMTP Configuration**: Setup email server settings
- **System Preferences**: Various system-wide preferences

## 🛠️ Technical Stack

- **Backend**: PHP 7.4+ with custom MVC framework
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, TailwindCSS, JavaScript
- **Icons**: Font Awesome
- **PDF Generation**: Built-in PDF generation
- **File Uploads**: Support for profile images and documents
- **Authentication**: Session-based with Google OAuth support

## 📋 Requirements

- **Web Server**: Apache 2.4+ (with mod_rewrite enabled)
- **PHP**: 7.4 or higher
- **MySQL**: 5.7 or higher
- **Extensions**: 
  - PDO MySQL
  - OpenSSL
  - Curl
  - GD Library
  - Mbstring

## 🚀 Installation

### 1. Clone the Repository
```bash
git clone https://github.com/siyamex/hrschool-management-system.git
cd hrschool-management-system
```

### 2. Server Setup
- Place the project in your web server document root (e.g., `htdocs` for XAMPP)
- Ensure Apache mod_rewrite is enabled
- Set appropriate file permissions for upload directories

### 3. Database Setup
```sql
-- Create database
CREATE DATABASE hrschool_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Import the schema
mysql -u root -p hrschool_db < database/schema.sql
```

### 4. Configuration
Update the database configuration in `config/bootstrap.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'hrschool_db');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('APP_URL', 'http://your-domain.com');
```

### 5. Create Sample Data (Optional)
```bash
php create_sample_data.php
```

### 6. Set Directory Permissions
```bash
chmod 755 public/uploads/
chmod 755 storage/
```

## 👤 Default Login

After installation, you can create an admin user through the registration process or use the sample data script to create test users.

**Sample Users** (if using create_sample_data.php):
- **Admin**: jane.doe@hrschool.com / password123
- **Staff**: john.smith@hrschool.com / password123

## 📁 Project Structure

```
hrschool-management-system/
├── app/
│   ├── controllers/     # Application controllers
│   ├── core/           # Core framework files
│   ├── helpers/        # Helper classes
│   ├── models/         # Data models
│   ├── services/       # Service classes
│   └── views/          # View templates
├── config/
│   └── bootstrap.php   # Application configuration
├── database/
│   └── schema.sql      # Database schema
├── public/
│   ├── assets/         # CSS, JS, images
│   └── uploads/        # Upload directory
├── storage/            # File storage
├── .htaccess          # Apache configuration
└── index.php          # Application entry point
```

## 🔧 Configuration Options

### Google OAuth Setup
1. Create a Google Cloud Project
2. Enable Google+ API
3. Create OAuth 2.0 credentials
4. Update settings in System Settings panel

### ZKTeco Integration
1. Configure device IP and port in System Settings
2. Ensure network connectivity to the device
3. Test connection using the sync feature

### Email Configuration
1. Configure SMTP settings in System Settings
2. Test email functionality
3. Customize email templates as needed

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/new-feature`)
3. Commit your changes (`git commit -am 'Add new feature'`)
4. Push to the branch (`git push origin feature/new-feature`)
5. Create a Pull Request

## 📝 API Documentation

The system includes RESTful API endpoints for:
- `/api/employees` - Employee data
- `/api/departments` - Department data
- Additional endpoints for mobile app integration

## 🐛 Common Issues & Solutions

### Issue: 404 Error on Routes
**Solution**: Ensure mod_rewrite is enabled and .htaccess is properly configured

### Issue: File Upload Errors
**Solution**: Check directory permissions and PHP upload limits

### Issue: Database Connection Failed
**Solution**: Verify database credentials and ensure MySQL is running

## 📊 Screenshots

<!-- Add screenshots here -->
*Dashboard Overview*
![Dashboard](screenshots/dashboard.png)

*Employee Management*
![Employees](screenshots/employees.png)

*Attendance Tracking*
![Attendance](screenshots/attendance.png)

## 🔮 Roadmap

- [ ] Mobile application (React Native/Flutter)
- [ ] Advanced reporting with charts
- [ ] Multi-tenant support
- [ ] Integration with more biometric devices
- [ ] REST API expansion
- [ ] Real-time notifications
- [ ] Document management system
- [ ] Performance appraisal module

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👨‍💻 Author

**Your Name**
- GitHub: [@yourusername](https://github.com/siyamex)
- Email: admin@siyamex.com

## 🙏 Acknowledgments

- TailwindCSS for the amazing utility-first CSS framework
- Font Awesome for the comprehensive icon library
- PHP community for continuous support
- All contributors who help improve this project

## 📞 Support

For support, email support@yourcompany.com or create an issue on GitHub.

---

⭐ **If you found this project helpful, please give it a star!** ⭐
