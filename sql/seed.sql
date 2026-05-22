-- Insert default admin user (password: Admin@123)
INSERT INTO users (username, password, email, full_name, role) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@lukufarm.com', 'System Administrator', 'admin');

-- Insert default settings
INSERT INTO settings (setting_key, setting_value, setting_type, category, description) VALUES
('site_name', 'LUKU Farm', 'text', 'general', 'Website name'),
('site_slogan', 'Expertise begets quality', 'text', 'general', 'Company slogan'),
('contact_email', 'info@lukufarm.com', 'email', 'contact', 'Primary contact email'),
('contact_phone', '+251-11-123-4567', 'text', 'contact', 'Primary contact phone'),
('address', 'Addis Ababa, Ethiopia', 'text', 'contact', 'Head office address'),
('currency', 'ETB', 'text', 'general', 'Default currency'),
('timezone', 'Africa/Addis_Ababa', 'text', 'general', 'System timezone');

-- Insert default delivery settings
INSERT INTO delivery_settings (setting_key, setting_value, description) VALUES
('delivery_enabled', 'true', 'Enable/disable delivery service'),
('min_weight_kg', '400', 'Minimum weight for delivery in KG'),
('delivery_fee', '1500', 'Base delivery fee in ETB'),
('delivery_fee_per_km', '50', 'Additional fee per kilometer'),
('free_delivery_above', '2000', 'Free delivery for orders above this amount');

-- Insert default pages
INSERT INTO pages (page_name, title_en, content_en) VALUES
('home', 'LUKU Farm - Poultry & Feed Solutions', '<h1>Welcome to LUKU Farm</h1><p>Your trusted partner in poultry farming and feed supply since 2010.</p>'),
('about', 'About LUKU Farm', '<h2>Our Story</h2><p>LUKU Farm is a professional poultry farm located in Addis Ababa, Ethiopia. With years of experience and a commitment to quality, we have become a trusted name in the poultry industry.</p><h3>Our Mission</h3><p>To provide high-quality poultry products and services that meet international standards while supporting local farmers.</p><h3>Our Vision</h3><p>To be Ethiopia''s leading poultry solutions provider, known for expertise and reliability.</p>'),
('partner', 'Jagdish Agro Industry - Official Partner', '<h2>Our Strategic Partner</h2><p>LUKU Farm is proud to be the official licensed distributor of Jagdish Agro Industry feeds in Addis Ababa.</p><p>Jagdish Agro Industry is a leading manufacturer of premium animal feeds, known for their quality and nutritional value.</p>');

-- Insert default translations
INSERT INTO translations (translation_key, en, am, om) VALUES
-- Navigation
('nav_home', 'Home', 'መነሻ', 'Mana'),
('nav_about', 'About', 'ስለ እኛ', 'Waa''ee'),
('nav_services', 'Services', 'አገልግሎቶች', 'Tajaajila'),
('nav_locations', 'Locations', 'አድራሻዎች', 'Iddoo'),
('nav_partner', 'Partner', 'አጋር', 'Hiriyya'),
('nav_contact', 'Contact', 'ያግኙን', 'Qunnamii'),
('nav_login', 'Login', 'ግባ', 'Seeni'),
('nav_logout', 'Logout', 'ውጣ', 'Ba''i'),

-- Common
('read_more', 'Read More', 'ተጨማሪ ያንብቡ', 'Dabalata'),
('learn_more', 'Learn More', 'ተጨማሪ ይማሩ', 'Dabalata Baradhaa'),
('send_message', 'Send Message', 'መልእክት ይላኩ', 'Ergaa Ergi'),
('our_branches', 'Our Branches', 'ቅርንጫፎቻችን', 'Dameewwan Keenya'),
('contact_us', 'Contact Us', 'ያግኙን', 'Nu Qunnamaa'),
('opening_hours', 'Opening Hours', 'የስራ ሰዓት', 'Sa''aatii Hojii'),
('call_us', 'Call Us', 'ይደውሉልን', 'Nu Bilbilaa'),
('email_us', 'Email Us', 'ኢሜይል ይላኩልን', 'Imeelii Nu Ergaa'),

-- Services page
('our_services', 'Our Services', 'የእኛ አገልግሎቶች', 'Tajaajila Keenya'),
('feed_delivery', 'Feed Delivery Service', 'የምግብ አቅርቦት አገልግሎት', 'Tajaajila Geessisa Nyaataa'),
('delivery_conditions', 'Delivery Conditions', 'የአቅርቦት ሁኔታዎች', 'Haala Geessisa'),
('min_order_weight', 'Minimum order weight', 'ዝቅተኛ የትእዛዝ ክብደት', 'Ultaataa ajaja xiqqaa'),
('delivery_fee', 'Delivery fee', 'የአቅርቦት ክፍያ', 'Kaffaltii Geessisa'),

-- Products
('our_feeds', 'Our Feeds', 'የእኛ ምግቦች', 'Nyaata Keenya'),
('starter_feed', 'Starter Feed (0-6 weeks)', 'የማስጀመሪያ ምግብ', 'Nyaata Jalqabaa'),
('grower_feed', 'Grower Feed (6-18 weeks)', 'የማደጊያ ምግብ', 'Nyaata Guddisaa'),
('layer_feed', 'Layer Feed (18+ weeks)', 'የምርት ምግብ', 'Nyaata Hanqaaquu'),
('broiler_feed', 'Broiler Feed', 'የብሮይለር ምግብ', 'Nyaata Broiler'),
('all_flock', 'All-Flock Feed', 'ለሁሉም ዶሮዎች ምግብ', 'Nyaata Hundaaf'),

-- Vaccines
('our_vaccines', 'Our Vaccines', 'የእኛ ክትባቶች', 'Talaallii Keenya'),
('newcastle_hitchner', 'Newcastle (Hitchner B1)', 'ኒውካስል (ሂችነር ቢ1)', 'Newcastle (Hitchner B1)'),
('newcastle_lasota', 'Newcastle (LaSota)', 'ኒውካስል (ላሶታ)', 'Newcastle (LaSota)'),
('newcastle_i2', 'Newcastle I-2 Thermostable', 'ኒውካስል አይ-2', 'Newcastle I-2'),
('fowl_pox', 'Fowl Pox', 'የዶሮ ፍንፉኝ', 'Fowl Pox'),
('gumboro', 'Gumboro Disease', 'ጉምቦሮ በሽታ', 'Gumboro'),

-- Medicines
('our_medicines', 'Our Medicines', 'መድሀኒቶቻችን', 'Qoricha Keenya'),
('antibiotics', 'Antibiotics', 'አንቲባዮቲክስ', 'Antibiotics'),
('antiparasitic', 'Antiparasitic', 'ፀረ ጥገኛ', 'Antiparasitic'),
('vitamins', 'Vitamins', 'ቫይታሚኖች', 'Vitamins'),
('electrolytes', 'Electrolytes', 'ኤሌክትሮላይቶች', 'Electrolytes'),

-- Locations
('address', 'Address', 'አድራሻ', 'Teessoo'),
('phone', 'Phone', 'ስልክ', 'Bilbila'),
('email', 'Email', 'ኢሜይል', 'Imeelii'),
('map_coordinates', 'Map Coordinates', 'የካርታ መጋጠሚያዎች', 'Kaardinaatii Kaartaa'),
('head_office', 'Head Office', 'ዋና መሥሪያ ቤት', 'Waajjira Guddichaa'),

-- Contact
('your_name', 'Your Name', 'ስምዎ', 'Maqaa Kee'),
('your_email', 'Your Email', 'ኢሜይልዎ', 'Imeelii Kee'),
('your_phone', 'Your Phone', 'ስልክዎ', 'Bilbila Kee'),
('subject', 'Subject', 'ርዕሰ ጉዳይ', 'Mata duree'),
('your_message', 'Your Message', 'መልእክትዎ', 'Ergaa Kee'),

-- Footer
('follow_us', 'Follow Us', 'ይከተሉን', 'Nu Hordofaa'),
('rights_reserved', 'All Rights Reserved', 'መብቱ በህግ የተጠበቀ ነው', 'Mirgni Hunduu Kan Qusname'),
('developed_by', 'Developed by', 'የተዘጋጀው በ', 'Kan Qophaa''ee'),

-- Dark/Light mode
('dark_mode', 'Dark Mode', 'ጨለማ ሞድ', 'Haala Dukkana'),
('light_mode', 'Light Mode', 'ብርሃን ሞድ', 'Haala Ifaa'),

-- Language names
('lang_en', 'English', 'እንግሊዝኛ', 'Ingliffa'),
('lang_am', 'Amharic', 'አማርኛ', 'Afaan Oromoo'),
('lang_om', 'Oromo', 'ኦሮምኛ', 'Afaan Oromoo');

-- Insert default services
INSERT INTO services (title_en, description_en, icon, display_order, is_active) VALUES
('Chicken Coop Construction', 'Professional construction of modern chicken coops with 2-5 floors, designed for optimal space utilization and bird health.', 'building', 1, true),
('Beginner Consultation', 'Expert guidance for new poultry farmers - from planning to first harvest.', 'chat', 2, true),
('Farm Setup Guidance', 'Complete farm setup assistance including equipment selection, layout planning, and operational procedures.', 'gear', 3, true),
('Beak Trimming', 'Humane and precise beak trimming services to prevent pecking and improve feed efficiency.', 'scissors', 4, true),
('Vaccination Services', 'Professional vaccination services using high-quality vaccines administered by trained veterinarians.', 'syringe', 5, true),
('Disease Prevention', 'Comprehensive disease prevention programs including biosecurity consulting and regular health checks.', 'shield', 6, true),
('Feed Supply', 'High-quality nutritional feeds for all stages of poultry development.', 'basket', 7, true),
('Medicine Supply', 'Complete range of veterinary medicines, vitamins, and supplements.', 'pill', 8, true),
('Slaughtered Chicken Supply', 'Fresh, hygienically processed chicken available for bulk orders.', 'chicken', 9, true),
('Feed Delivery Service', 'Convenient feed delivery service available for orders above 400 KG within Addis Ababa.', 'truck', 10, true);

-- Insert default feeds
INSERT INTO feeds (name_en, description_en, type, stage, form, is_medicated, display_order) VALUES
('Starter Feed', 'Complete balanced nutrition for chicks 0-6 weeks. High protein for optimal growth.', 'Starter', '0-6 weeks', 'Crumbles', false, 1),
('Grower Feed', 'Specially formulated for growing pullets 6-18 weeks. Supports healthy development.', 'Grower', '6-18 weeks', 'Pellets', false, 2),
('Layer Feed', 'Optimal calcium and nutrients for maximum egg production in layers 18+ weeks.', 'Layer', '18+ weeks', 'Mash', false, 3),
('Broiler Starter', 'High-energy, high-protein feed for rapid growth in broiler chicks.', 'Broiler', '0-3 weeks', 'Crumbles', true, 4),
('Broiler Finisher', 'Complete feed for broilers 3+ weeks for efficient weight gain.', 'Broiler', '3+ weeks', 'Pellets', true, 5),
('All-Flock Feed', 'Versatile feed suitable for all poultry types and ages.', 'All-Flock', 'All stages', 'Mash', false, 6),
('Medicated Starter', 'Starter feed with added coccidiostat for disease prevention.', 'Starter', '0-6 weeks', 'Crumbles', true, 7),
('Organic Layer Mash', 'Certified organic feed for premium egg production.', 'Layer', '18+ weeks', 'Mash', false, 8);

-- Insert default vaccines
INSERT INTO vaccines (name_en, description_en, application_method, age_recommendation, display_order) VALUES
('Newcastle Disease (Hitchner B1)', 'Mild vaccine for day-old chicks, provides early protection against Newcastle disease.', 'Eye drop or intranasal', 'Day 1-7', 1),
('Newcastle Disease (LaSota)', 'Standard Newcastle vaccine, booster immunization.', 'Drinking water or spray', 'Day 14-21', 2),
('Newcastle I-2 Thermostable', 'Heat-stable Newcastle vaccine, ideal for village chickens.', 'Eye drop or food', 'Any age from day 7', 3),
('Inactivated Newcastle Oil', 'Killed vaccine for long-term immunity in layers and breeders.', 'Injection', 'Week 8-12', 4),
('Fowl Pox', 'Protects against fowl pox, pigeon pox, and canary pox.', 'Wing web stab', 'Week 6-10', 5),
('Fowl Typhoid', 'Salmonella Gallinarum vaccine for layers.', 'Injection', 'Week 8-10', 6),
('Infectious Bursal Disease (Gumboro)', 'Intermediate strain for Gumboro protection.', 'Drinking water', 'Day 10-14', 7),
('Infectious Bronchitis', 'Massachusetts strain for respiratory protection.', 'Spray or drinking water', 'Day 1-7', 8);

-- Insert default medicines
INSERT INTO medicines (name_en, description_en, category, usage_en, dosage, display_order) VALUES
('Enrofloxacin', 'Broad-spectrum antibiotic for respiratory and digestive infections.', 'Antibiotic', 'Mix in drinking water for 3-5 days.', '10mg/kg body weight', 1),
('Oxytetracycline', 'Long-acting antibiotic for bacterial infections.', 'Antibiotic', 'Injection or oral administration.', '20mg/kg', 2),
('Tylosin', 'Effective against mycoplasma and chronic respiratory disease.', 'Antibiotic', 'Dissolve in drinking water.', '25mg/L water', 3),
('Sulphadiazine + Trimethoprim', 'Combination antibiotic for coccidiosis and bacterial infections.', 'Antibiotic', 'Oral powder, mix with feed.', '30mg/kg', 4),
('Piperazine', 'Roundworm dewormer for poultry.', 'Antiparasitic', 'Mix with feed or water.', '100mg/kg', 5),
('Amprolium', 'Coccidiostat for prevention and treatment of coccidiosis.', 'Antiparasitic', 'Add to drinking water for 5-7 days.', '0.0125% in water', 6),
('Toltrazuril', 'Broad-spectrum anticoccidial agent.', 'Antiparasitic', 'Single dose in drinking water.', '7mg/kg', 7),
('Multivitamin Supplement', 'Complete vitamin and mineral supplement for stress recovery.', 'Support', 'Mix with drinking water.', '1g/L water', 8),
('Electrolyte Powder', 'Rehydration solution for sick or stressed birds.', 'Support', 'Dissolve in clean drinking water.', '2g/L water', 9),
('Probiotic Booster', 'Gut health supplement for improved digestion.', 'Support', 'Add to feed daily.', '0.5kg/ton feed', 10);

-- Insert default locations (branches)
INSERT INTO locations (branch_name_en, address_en, phone, latitude, longitude, opening_hours_en, description_en, is_head_office, display_order) VALUES
('LUKU Farm - Head Office', 'Bole Sub-city, Woreda 3, Addis Ababa', '+251-11-661-1234', 8.9806, 38.7578, 'Monday - Friday: 8:00 AM - 6:00 PM, Saturday: 9:00 AM - 2:00 PM', 'Main office and flagship farm location.', true, 1),
('LUKU Farm - Merkato Branch', 'Addis Ketema Sub-city, Merkato Area, Addis Ababa', '+251-11-551-5678', 9.0300, 38.7400, 'Monday - Saturday: 8:30 AM - 7:00 PM', 'Feed supply and veterinary services.', false, 2),
('LUKU Farm - Summit Branch', 'Gulele Sub-city, Summit Area, Addis Ababa', '+251-11-811-9012', 9.0500, 38.7100, 'Monday - Saturday: 8:30 AM - 6:30 PM', 'Poultry products and consultation services.', false, 3),
('LUKU Farm - Kaliti Branch', 'Nifas Silk-Lafto Sub-city, Kaliti, Addis Ababa', '+251-11-441-3456', 8.8900, 38.7800, 'Monday - Friday: 8:00 AM - 6:00 PM, Sunday: 9:00 AM - 12:00 PM', 'Vaccination center and feed distribution point.', false, 4),
('LUKU Farm - CMC Branch', 'Bole Sub-city, CMC Area, Addis Ababa', '+251-11-667-7890', 8.9900, 38.8100, 'Monday - Saturday: 9:00 AM - 6:00 PM', 'Modern poultry showroom and customer service center.', false, 5);