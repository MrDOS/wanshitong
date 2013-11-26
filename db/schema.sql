-- COMP 3753 -- Project Part #4: Database Creation
-- Samuel Coleman, 100105709
-- Kate-Lynn MacPhail, 100096539
-- Nicholas Wetmore, 100104702
--
-- Schema definition.

-- Tear down foreign keys in reverse order
DROP TABLE IF EXISTS student_sections;
DROP TABLE IF EXISTS section_books;
DROP TABLE IF EXISTS book_authors;
DROP TABLE IF EXISTS order_books;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS sections;
DROP TABLE IF EXISTS courses;
DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS staff;
DROP TABLE IF EXISTS authors;
DROP TABLE IF EXISTS books;
DROP TABLE IF EXISTS departments;

-- Departments
CREATE TABLE departments (
	department_code CHAR(4) NOT NULL PRIMARY KEY
);

-- Books
CREATE TABLE books (
	isbn BIGINT(13) UNSIGNED NOT NULL PRIMARY KEY,
	title VARCHAR(255) NOT NULL,
	quantity INT UNSIGNED NOT NULL DEFAULT 0,
	price INT UNSIGNED NOT NULL DEFAULT 0,
	stocked BOOLEAN NOT NULL DEFAULT 1
);

-- Authors
CREATE TABLE authors (
	author_id INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	given_name VARCHAR(255),
	family_name VARCHAR(255) NOT NULL,
	sort_name VARCHAR(512),		-- Todo: trigger to automatically generate
	display_name VARCHAR(511)	--		 sort/display names
);

-- Staff
CREATE TABLE staff (
	username VARCHAR(255) NOT NULL PRIMARY KEY,
	password CHAR(40) NOT NULL,
	password_salt CHAR(8) NOT NULL
);

-- Students
CREATE TABLE students (
	student_number INT(9) UNSIGNED NOT NULL PRIMARY KEY,
	email VARCHAR(254)
);

-- Courses
CREATE TABLE courses (
	department_code CHAR(4) NOT NULL,
	course_number INT(4) UNSIGNED NOT NULL,
	FOREIGN KEY (department_code) REFERENCES departments(department_code),
	PRIMARY KEY (department_code, course_number)
);

-- Sections
CREATE TABLE sections (
	department_code CHAR(4) NOT NULL,
	course_number INT(4) UNSIGNED NOT NULL,
	section_number INT UNSIGNED NOT NULL,
	slot CHAR(2) NOT NULL,
	year SMALLINT UNSIGNED NOT NULL,
	term TINYINT UNSIGNED NOT NULL,
	FOREIGN KEY (department_code, course_number) REFERENCES courses(department_code, course_number),
	PRIMARY KEY (department_code, course_number, section_number)
);

-- Orders
CREATE TABLE orders (
	order_id INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	order_date DATE NOT NULL,
	arrival_date DATE,
	student_number INT(10) UNSIGNED NOT NULL,
	FOREIGN KEY (student_number) REFERENCES students(student_number)
);

-- Relating sections to books
CREATE TABLE section_books (
	department_code CHAR(4) NOT NULL,
	course_number INT(4) UNSIGNED NOT NULL,
	section_number INT UNSIGNED NOT NULL,
	isbn BIGINT(13) UNSIGNED NOT NULL,
	FOREIGN KEY (department_code, course_number, section_number) REFERENCES sections(department_code, course_number, section_number),
	FOREIGN KEY (isbn) REFERENCES books(isbn),
	PRIMARY KEY (department_code, course_number, section_number, isbn)
);

-- Relating authors to books
CREATE TABLE book_authors (
	isbn BIGINT(13) UNSIGNED NOT NULL,
	author_id INT UNSIGNED NOT NULL,
	FOREIGN KEY (isbn) REFERENCES books(isbn),
	FOREIGN KEY (author_id) REFERENCES authors(author_id),
	PRIMARY KEY (isbn, author_id)
);

-- Relating books to orders
CREATE TABLE order_books (
	order_id INT UNSIGNED NOT NULL,
	isbn BIGINT(13) UNSIGNED NOT NULL,
	quantity INT UNSIGNED NOT NULL,
	FOREIGN KEY (order_id) REFERENCES orders(order_id),
	FOREIGN KEY (isbn) REFERENCES books(isbn),
	PRIMARY KEY (order_id, isbn)
);

-- Relating sections to students
CREATE TABLE student_sections (
	student_number INT(9) UNSIGNED NOT NULL,
	department_code CHAR(4) NOT NULL,
	course_number INT(4) UNSIGNED NOT NULL,
	section_number INT UNSIGNED NOT NULL,
	FOREIGN KEY (student_number) REFERENCES students(student_number),
	FOREIGN KEY (department_code, course_number, section_number) REFERENCES sections(department_code, course_number, section_number),
	PRIMARY KEY (student_number, department_code, course_number, section_number)
);