# uCMS - A Micro Content Management System

## Introduction

uCMS also stylized MicroCMS is a miniature blog engine.

## Tools used

1. Xdebug
    - Xdebug helper for chrome.
2. Composer
3. DBeaver: as a vendor agnostic DB browser.
4. Postgresql:
    - pdo_pgsql driver.
5. UUID.
6. Usage of type annotations.

## Design Documentation

PDO (PHP Data Objects) offers a more robust and secure approach to database interaction compared to older procedural methods. It provides a consistent interface for various database systems, allowing for easier switching between databases if needed.  Crucially, PDO's use of prepared statements helps prevent SQL injection vulnerabilities, a significant security risk in procedural approaches.  Furthermore, PDO offers better error handling through exceptions, making it easier to manage and debug database-related issues.  Overall, PDO promotes cleaner, more maintainable, and secure code, aligning with modern PHP best practices.
