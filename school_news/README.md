# README.md

# School News Project

Welcome to the School News project! This application is designed to manage news articles, user authentication, and administrative functions for a school news platform.

## Project Structure

- **config/**: Contains configuration files for database connections and site settings.
- **core/**: Contains main class files that handle business logic, including authentication, article management, and user management.
- **public/**: The only directory accessible to web users, containing the main entry point and all assets (CSS, JS, images).
- **includes/**: Contains reusable components like headers and footers, as well as helper functions.
- **admin/**: A separate section for administrative functions, protected by authentication.
- **views/**: Contains all template files, organized by feature (articles, auth, admin).
- **handlers/**: Contains PHP scripts that process form submissions and implement security measures.

## Installation

1. Clone the repository:
   ```
   git clone <repository-url>
   ```
2. Navigate to the project directory:
   ```
   cd school_news
   ```
3. Install dependencies using Composer:
   ```
   composer install
   ```

## Usage

- Access the application through the `public/index.php` file.
- Use the admin section for managing articles, categories, users, and comments.

## Contributing

Contributions are welcome! Please submit a pull request or open an issue for any enhancements or bug fixes.

## License

This project is licensed under the MIT License. See the LICENSE file for details.