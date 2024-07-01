# Radar Hub

Radar Hub is a content-based filtering system for news using RSS API. It provides personalized news recommendations based on user preferences and reading history.

## Features

- News aggregation from various RSS feeds
- Content-based filtering for personalized recommendations
- User authentication and registration
- Fast NLP model for topic analysis
- Responsive web interface

## Prerequisites

- XAMPP (or similar local server environment with PHP and MySQL)
- Web browser
- Git (optional, for cloning the repository)

## Setup Instructions

1. **Install XAMPP**:
   If you haven't already, download and install XAMPP from [https://www.apachefriends.org/](https://www.apachefriends.org/)

2. **Clone or Download the Repository**:
   - If using Git: `git clone https://github.com/your-username/radar_hub.git`
   - Or download and extract the ZIP file from the repository

3. **Move Project Files**:
   Move the project folder to the `htdocs` directory in your XAMPP installation (usually `C:\xampp\htdocs` on Windows or `/Applications/XAMPP/htdocs` on macOS)

4. **Start XAMPP**:
   Launch the XAMPP Control Panel and start the Apache and MySQL services

5. **Set Up the Database**:
   - Open your web browser and go to `http://localhost/phpmyadmin`
   - Create a new database named `radar_hub` (or your preferred name)
   - Import the SQL file provided in the project (look for a file with a `.sql` extension in the project directory)

6. **Configure Database Connection**:
   Open the project's configuration file (likely `config.php` or similar) and update the database connection details if necessary

7. **Access the Application**:
   Open your web browser and navigate to `http://localhost/radar_hub` (adjust the path if you placed the project in a subdirectory)

## Usage

1. Register for a new account or log in if you already have one
2. Browse through the news articles or use the search functionality
3. Interact with articles to improve your personalized recommendations

## Development

- The `app.js` and `app2.js` files contain the core application logic
- `News` and `Recommendation` folders likely contain related functionalities
- The `api` folder contains backend API endpoints
- Frontend files include `index.html`, `login.html`, `register.html`, and their corresponding CSS and JS files

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is under the [insert license type] license. See the LICENSE file for more details.
