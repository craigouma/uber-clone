# Uber Clone

This project is a combination of a Laravel admin panel and a React Native mobile app.

## Description

The project aims to create a clone of the Uber app, providing users with a seamless experience for booking rides and managing their transportation needs. The Laravel admin panel serves as the backend for managing drivers, vehicles, and other administrative tasks, while the React Native mobile app provides a user-friendly interface for customers to book rides and track their journeys.

## Features

- User registration and authentication
- Ride booking and tracking
- Real-time location tracking
- Payment integration
- Driver management
- Vehicle management
- Admin dashboard

## Installation

1. Clone the repository.
2. Install the necessary dependencies for both the Laravel admin panel and the React Native mobile app.
3. Configure the database connection in the Laravel admin panel.
4. Run the migrations and seed the database.
5. Start the Laravel server and the React Native development server.

## Usage

1. Access the Laravel admin panel by navigating to the admin URL.
2. Use the admin panel to manage drivers, vehicles, and other administrative tasks.
3. Install the React Native app on your mobile device.
4. Register or log in to the app.
5. Use the app to book rides, track your journey, and make payments.

## Installation

To install and run this project, follow these steps:

1. Clone the repository by running the following command in your terminal:
    ```
    git clone https://github.com/craigouma/uber-clone.git
    ```
2. Install the necessary dependencies for both the Laravel admin panel and the React Native mobile app. In the root directory of the project, run the following commands:
    ```
    cd laravel-admin-panel
    composer install
    ```
    ```
    cd react-native-app
    npm install
    ```
3. Configure the database connection in the Laravel admin panel. Open the `.env` file in the `laravel-admin-panel` directory and update the following lines with your database credentials:
    ```
    DB_CONNECTION=mysql
    DB_HOST=your-database-host
    DB_PORT=your-database-port
    DB_DATABASE=your-database-name
    DB_USERNAME=your-database-username
    DB_PASSWORD=your-database-password
    ```
4. Run the migrations and seed the database. In the `laravel-admin-panel` directory, run the following commands:
    ```
    php artisan migrate
    ```
    ```
    php artisan db:seed
    ```
5. Start the Laravel server. In the `laravel-admin-panel` directory, run the following command:
    ```
    php artisan serve
    ```
6. Start the React Native development server. In the `react-native-app` directory, run the following command:
    ```
    npx react-native start
    ```
7. Install the React Native app on your mobile device. In the `react-native-app` directory, run the following command:
    ```
    npx react-native run-android
    ```
    or
    ```
    npx react-native run-ios
    ```
8. Register or log in to the app using the provided credentials.
9. Use the app to book rides, track your journey, and make payments.

## Contributing

Contributions are welcome! If you have any suggestions or improvements, please submit a pull request.

## License

This project is licensed under the [MIT License](LICENSE).
