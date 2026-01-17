INSERT INTO users (full_name, email, password_hash, role, status)
VALUES
  ('Admin User', 'admin@eaa.local', '$2y$10$Z5qCydHFIr.54C8Xg3h5jOmVkK.DRjwfo/gPuwvk0yjDg8V4Qxk2m', 'admin', 'active'),
  ('Member User', 'member@eaa.local', '$2y$10$Z5qCydHFIr.54C8Xg3h5jOmVkK.DRjwfo/gPuwvk0yjDg8V4Qxk2m', 'member', 'active'),
  ('Vendor User', 'vendor@eaa.local', '$2y$10$Z5qCydHFIr.54C8Xg3h5jOmVkK.DRjwfo/gPuwvk0yjDg8V4Qxk2m', 'vendor', 'active');

INSERT INTO member_profile (user_id, phone, membership_category, coa_number, organization_name)
VALUES ((SELECT id FROM users WHERE email = 'member@eaa.local'), '+91 90000 00000', 'licensed', 'CA/2012/55432', 'EAA Studio');

INSERT INTO vendor_profile (user_id, company_name, contact_name, phone, material_category, description, address, website)
VALUES (
  (SELECT id FROM users WHERE email = 'vendor@eaa.local'),
  'EAA Partner Studio',
  'Vendor Contact',
  '+91 98765 43210',
  'Glass & Glazing',
  'Preferred partner for glazing solutions.',
  '123 Vendor Lane, Erode',
  'https://vendor.example.com'
);

INSERT INTO vendor_products (vendor_id, name, category, price, unit, location, image_url, status)
VALUES
  (
    (SELECT id FROM vendor_profile WHERE company_name = 'EAA Partner Studio'),
    'High-Strength Structural Steel',
    'Structural Steel',
    185.00,
    'SQFT',
    'Erode',
    'https://images.unsplash.com/photo-1513828583688-c52646db42da?w=800&q=80',
    'active'
  ),
  (
    (SELECT id FROM vendor_profile WHERE company_name = 'EAA Partner Studio'),
    'Reflective Toughened Glass Panels',
    'Glass & Glazing',
    455.00,
    'SQFT',
    'Erode',
    'https://images.unsplash.com/photo-1518005020951-eccb494ad742?w=800&q=80',
    'active'
  );
