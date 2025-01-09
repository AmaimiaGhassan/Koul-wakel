# Mealplan Application

This is a Symfony application for managing meal plans. It allows users to create, edit, view, and delete meal plans, including uploading and displaying images for each meal plan.

## Features

- Create new meal plans
- Edit existing meal plans
- View details of meal plans
- Delete meal plans
- Upload and display images for meal plans

## Installation

1. Clone the repository:
    ```sh
    git clone https://github.com/AmaimiaGhassan/Koul-wakel
    ```

2. Navigate to the project directory:
    ```sh
    cd koul-wakel
    ```

3. Install dependencies:
    ```sh
    composer install
    ```

4. Set up the database:
    ```sh
    php bin/console doctrine:database:create
    php bin/console doctrine:schema:update --force
    ```

5. Set up the photos directory:
    ```sh
    mkdir -p public/uploads/photos
    ```

6. Start the Symfony server:
    ```sh
    symfony server:start
    ```

## Usage

- Access the application in your web browser at `http://localhost:8000`.
- Use the navigation menu to create, edit, view, and delete meal plans.

## Contributing

Contributions are welcome! Please open an issue or submit a pull request.

## License

This project is licensed under the MIT License.