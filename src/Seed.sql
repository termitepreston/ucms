

-- Roles Table
CREATE TABLE roles (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,
    description TEXT
);

-- Permissions Table
CREATE TABLE permissions (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,
    description TEXT
);

-- Role Permissions (Associative Table - Many-to-Many relationship between roles and permissions)
CREATE TABLE role_permissions (
    role_id INT REFERENCES roles(id) ON DELETE CASCADE,
    permission_id INT REFERENCES permissions(id) ON DELETE CASCADE,
    PRIMARY KEY (role_id, permission_id)
);

-- Sample 'users' table (assuming it exists for user management in uCMS - adapt to your actual table)
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(255) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    first_name VARCHAR(255),
    last_name VARCHAR(255),
    profile_picture_path VARCHAR(255),
    bio TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP null,
    blog_title TEXT
);

-- User Roles (Associative Table - Many-to-Many relationship between users and roles)
CREATE TABLE user_roles (
    user_id INT REFERENCES users(id) ON DELETE CASCADE, -- Assuming you have a 'users' table with 'id' as primary key
    role_id INT REFERENCES roles(id) ON DELETE CASCADE,
    PRIMARY KEY (user_id, role_id)
);



-- Insert Roles
INSERT INTO roles (name, description) VALUES
    ('admin', 'Administrator role with full access'),
    ('user', 'Regular user role for blog creation and page management');

-- Insert Permissions
INSERT INTO permissions (name, description) VALUES
    ('manage_users', 'Permission to manage user accounts (create, edit, delete users)'),
    ('create_blog_project', 'Permission to create new blog projects for users'),
    ('add_page', 'Permission to add pages to their own blog project'),
    ('remove_page', 'Permission to remove pages from their own blog project');

-- Assign Permissions to Roles (Role Permissions)
INSERT INTO role_permissions (role_id, permission_id)
VALUES
    -- Admin Role Permissions
    ((SELECT id FROM roles WHERE name = 'admin'), (SELECT id FROM permissions WHERE name = 'manage_users')),
    ((SELECT id FROM roles WHERE name = 'admin'), (SELECT id FROM permissions WHERE name = 'create_blog_project')),
    ((SELECT id FROM roles WHERE name = 'admin'), (SELECT id FROM permissions WHERE name = 'add_page')), -- Admin implicitly has user permissions too, or explicitly if needed
    ((SELECT id FROM roles WHERE name = 'admin'), (SELECT id FROM permissions WHERE name = 'remove_page')), -- Admin implicitly has user permissions too, or explicitly if needed

    -- User Role Permissions
    ((SELECT id FROM roles WHERE name = 'user'), (SELECT id FROM permissions WHERE name = 'add_page')),
    ((SELECT id FROM roles WHERE name = 'user'), (SELECT id FROM permissions WHERE name = 'remove_page'));
   
CREATE table projects (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE, -- Assuming User IDs are INTs
    description TEXT NULL,
    domain VARCHAR(255) UNIQUE NULL,
    language VARCHAR(10) NULL,
    logo_path VARCHAR(255) NULL,
    created_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP WITH TIME ZONE NULL  
);    
    
create table blogs (
	id SERIAL primary key,
	title varchar(255) not null,
	slug varchar(255) unique not null,
	content text,
	author_id int references users(id) on delete set null
);

	
         
  