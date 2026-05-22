-- Create database
CREATE DATABASE luku_farm;
\c luku_farm;

-- Enable PostgreSQL extensions
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- Users table
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    full_name VARCHAR(100),
    role VARCHAR(20) DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE
);

-- Services table
CREATE TABLE services (
    id SERIAL PRIMARY KEY,
    title_en VARCHAR(100) NOT NULL,
    title_am VARCHAR(100),
    title_om VARCHAR(100),
    description_en TEXT,
    description_am TEXT,
    description_om TEXT,
    icon VARCHAR(50),
    image_url VARCHAR(255),
    display_order INTEGER DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products table (general products)
CREATE TABLE products (
    id SERIAL PRIMARY KEY,
    name_en VARCHAR(100) NOT NULL,
    name_am VARCHAR(100),
    name_om VARCHAR(100),
    description_en TEXT,
    description_am TEXT,
    description_om TEXT,
    category VARCHAR(50),
    price DECIMAL(10,2),
    unit VARCHAR(20),
    image_url VARCHAR(255),
    display_order INTEGER DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Feeds table
CREATE TABLE feeds (
    id SERIAL PRIMARY KEY,
    name_en VARCHAR(100) NOT NULL,
    name_am VARCHAR(100),
    name_om VARCHAR(100),
    description_en TEXT,
    description_am TEXT,
    description_om TEXT,
    type VARCHAR(50),
    stage VARCHAR(50),
    form VARCHAR(50),
    price DECIMAL(10,2),
    unit VARCHAR(20),
    image_url VARCHAR(255),
    is_medicated BOOLEAN DEFAULT FALSE,
    display_order INTEGER DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Vaccines table
CREATE TABLE vaccines (
    id SERIAL PRIMARY KEY,
    name_en VARCHAR(100) NOT NULL,
    name_am VARCHAR(100),
    name_om VARCHAR(100),
    description_en TEXT,
    description_am TEXT,
    description_om TEXT,
    application_method VARCHAR(100),
    age_recommendation VARCHAR(100),
    price DECIMAL(10,2),
    image_url VARCHAR(255),
    display_order INTEGER DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Medicines table
CREATE TABLE medicines (
    id SERIAL PRIMARY KEY,
    name_en VARCHAR(100) NOT NULL,
    name_am VARCHAR(100),
    name_om VARCHAR(100),
    description_en TEXT,
    description_am TEXT,
    description_om TEXT,
    category VARCHAR(50),
    usage_en TEXT,
    usage_am TEXT,
    usage_om TEXT,
    dosage VARCHAR(100),
    price DECIMAL(10,2),
    image_url VARCHAR(255),
    display_order INTEGER DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Locations table (Branches)
CREATE TABLE locations (
    id SERIAL PRIMARY KEY,
    branch_name_en VARCHAR(100) NOT NULL,
    branch_name_am VARCHAR(100),
    branch_name_om VARCHAR(100),
    address_en TEXT NOT NULL,
    address_am TEXT,
    address_om TEXT,
    phone VARCHAR(50) NOT NULL,
    phone_secondary VARCHAR(50),
    email VARCHAR(100),
    latitude DECIMAL(10,8),
    longitude DECIMAL(11,8),
    opening_hours_en TEXT,
    opening_hours_am TEXT,
    opening_hours_om TEXT,
    description_en TEXT,
    description_am TEXT,
    description_om TEXT,
    image_url VARCHAR(255),
    display_order INTEGER DEFAULT 0,
    is_head_office BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Delivery settings table
CREATE TABLE delivery_settings (
    id SERIAL PRIMARY KEY,
    setting_key VARCHAR(50) UNIQUE NOT NULL,
    setting_value TEXT,
    description TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_by INTEGER REFERENCES users(id)
);

-- Pages table (for CMS)
CREATE TABLE pages (
    id SERIAL PRIMARY KEY,
    page_name VARCHAR(50) UNIQUE NOT NULL,
    title_en VARCHAR(200),
    title_am VARCHAR(200),
    title_om VARCHAR(200),
    content_en TEXT,
    content_am TEXT,
    content_om TEXT,
    meta_description_en TEXT,
    meta_description_am TEXT,
    meta_description_om TEXT,
    is_published BOOLEAN DEFAULT TRUE,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_by INTEGER REFERENCES users(id)
);

-- Translations table
CREATE TABLE translations (
    id SERIAL PRIMARY KEY,
    translation_key VARCHAR(100) UNIQUE NOT NULL,
    en TEXT NOT NULL,
    am TEXT,
    om TEXT,
    category VARCHAR(50),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Messages table (Contact form)
CREATE TABLE messages (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(50),
    subject VARCHAR(200),
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    is_replied BOOLEAN DEFAULT FALSE,
    replied_at TIMESTAMP,
    replied_by INTEGER REFERENCES users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Images table
CREATE TABLE images (
    id SERIAL PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    original_filename VARCHAR(255),
    filepath VARCHAR(500) NOT NULL,
    filesize INTEGER,
    mime_type VARCHAR(100),
    title_en VARCHAR(200),
    title_am VARCHAR(200),
    title_om VARCHAR(200),
    alt_text_en VARCHAR(200),
    alt_text_am VARCHAR(200),
    alt_text_om VARCHAR(200),
    category VARCHAR(50),
    display_order INTEGER DEFAULT 0,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    uploaded_by INTEGER REFERENCES users(id)
);

-- Settings table
CREATE TABLE settings (
    id SERIAL PRIMARY KEY,
    setting_key VARCHAR(50) UNIQUE NOT NULL,
    setting_value TEXT,
    setting_type VARCHAR(20) DEFAULT 'text',
    category VARCHAR(50),
    description TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create indexes
CREATE INDEX idx_services_active ON services(is_active);
CREATE INDEX idx_feeds_active ON feeds(is_active);
CREATE INDEX idx_vaccines_active ON vaccines(is_active);
CREATE INDEX idx_medicines_active ON medicines(is_active);
CREATE INDEX idx_locations_active ON locations(is_active);
CREATE INDEX idx_messages_created ON messages(created_at);
CREATE INDEX idx_translations_key ON translations(translation_key);

-- Create trigger function for updated_at
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ language 'plpgsql';

-- Create triggers
CREATE TRIGGER update_services_updated_at BEFORE UPDATE ON services
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_feeds_updated_at BEFORE UPDATE ON feeds
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_vaccines_updated_at BEFORE UPDATE ON vaccines
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_medicines_updated_at BEFORE UPDATE ON medicines
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_locations_updated_at BEFORE UPDATE ON locations
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_delivery_settings_updated_at BEFORE UPDATE ON delivery_settings
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_pages_updated_at BEFORE UPDATE ON pages
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_translations_updated_at BEFORE UPDATE ON translations
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_settings_updated_at BEFORE UPDATE ON settings
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();