```markdown
# School News Portal

A dynamic news portal built with PHP that allows schools to manage and publish news articles, handle user comments, and maintain an organized content structure.

## Features

- Article Management System
- Category Organization
- User Comments
- Breaking News Section
- Social Media Integration
- Newsletter Subscription
- Responsive Design
- SEO-friendly URLs
- Image Upload Support
- View Counter

## Requirements

- PHP 8.1 or higher
- MySQL 5.7 or higher
- Composer
- Web server (Apache/Nginx)

## Installation

1. Clone the repository:
```bash
git clone https://github.com/EL-HOUSS-BRAHIM/School-News-Portal
```

2. Install dependencies:
```bash
composer install
```

3. Configure your database:
- Create a new MySQL database
- Copy `config/database.config.example.php` to 

database.config.php


- Update the database credentials in 

database.config.php



4. Set up your web server:
- Point your web server's document root to the 

public

 directory
- Ensure mod_rewrite is enabled (Apache)

5. Initialize the database:
```bash
php setup/init.php
```

## Configuration

- 

app.php

: General application settings
- 

contact.php

: Contact and social media information
- 

database.config.php

: Database credentials

## Directory Structure

```

```

## Usage

1. Access the admin panel at `/dashboard`
2. Create categories for your articles
3. Start publishing news articles
4. Manage comments and user interactions

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

**License**
Copyright (c) 2024 BRAHIM EL HOUSS

All rights reserved.

Permission is not granted to any person to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of this software and associated documentation files (the "Software"), without explicit written permission from the copyright holder.

## Contact

BRAHIM EL HOUSS/FULL STACK SOFTWEAR ENGINEER - [@EL-HOUSS-BRAHIM](https://github.com/EL-HOUSS-BRAHIM/)

Project Link: [https://github.com/EL-HOUSS-BRAHIM/School-News-Portal](https://github.com/EL-HOUSS-BRAHIM/School-News-Portal)
```