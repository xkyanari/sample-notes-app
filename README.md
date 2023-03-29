# Sample Notes App

This is a responsive webpage that behaves somewhat similar to iOS Notes app but in Dark mode. The webpage has three responsive columns: Folders, Notes and the form itself. Folders and Notes are self-explanatory while the form is where you can load and create notes. Since the page is responsive, it fits even smaller resolutions where you can focus on the form. The app supports MYSQL 8, PHP with MySQLi extension and JS.

## Table of Contents

- [Installation](#installation)
- [Usage](#usage)
- [Contributing](#contributing)
- [License](#license)

## Installation

To install and run the project, follow these steps:

1. Clone this repository to your local machine using `git clone https://github.com/xkyanari/sample-notes-app.git`
2. Install a local web server like XAMPP or WAMP
3. Get the schema from schema.txt to create the tables into your MYSQL 8 server
4. Copy the `sample-notes-app` folder to your local web server's root directory
5. Start the server and visit `http://localhost/sample-notes-app/` in your browser
6. Adjust the code to your liking

## Usage

To use the app, follow these steps:

1. Open the app in your browser
2. Click on any folder to view its notes
3. Click on any note to edit or delete it
4. Fill in the note's details and click the "Submit" button
5. Notes can also be assigned per folder by selecting on the dropdown menu

## Contributing

If you'd like to contribute to the project, please follow these steps:

1. Fork the project
2. Create a new branch (`git checkout -b feature`)
3. Make your changes and commit them (`git commit -am 'Add feature'`)
4. Push to the branch (`git push origin feature`)
5. Create a new Pull Request

## License

This project is licensed under the [MIT License](https://opensource.org/licenses/MIT).
