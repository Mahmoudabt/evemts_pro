-- ============================================================
-- قاعدة البيانات: city_events
-- تشمل: جدول users (للمشرفين) + جدول events (للفعاليات)
-- ============================================================

CREATE DATABASE IF NOT EXISTS city_events
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE city_events;

-- ============================================================
-- جدول المشرفين (users)
-- كلمة المرور مخزنة كـ bcrypt hash
-- القيمة الافتراضية: user=admin / password=admin
-- ============================================================
CREATE TABLE IF NOT EXISTS users (
    id       INT          PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role     ENUM('admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إدراج المشرف الافتراضي (admin / admin)
INSERT INTO users (username, password, role)
VALUES ('admin', '$2y$10$hSnStQlWImdlPol3G306m.nSNeVhYWatHsZ6br7t/OERtoz/vgtB6', 'admin')
ON DUPLICATE KEY UPDATE role = 'admin';

-- إدراج مستخدم عادي افتراضي (user1 / user123)
INSERT INTO users (username, password, role)
VALUES ('user1', '$2b$10$LA4bF7gvaYaLuYkLbVNpzecVqb130unAuL7ZQ7OgX8DbBMLWtmsWC', 'user')
ON DUPLICATE KEY UPDATE username = username;

-- ============================================================
-- جدول الفعاليات (events)
-- ============================================================
CREATE TABLE IF NOT EXISTS events (
    id          INT           PRIMARY KEY AUTO_INCREMENT,
    title       VARCHAR(255)  NOT NULL,
    description TEXT,
    category    VARCHAR(50),
    location    VARCHAR(255),
    event_date  DATE,
    image       VARCHAR(255)  DEFAULT 'default.png',
    created_at  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- جدول رسائل التواصل (contacts)
-- يُستخدم من صفحة contact.php
-- ============================================================
CREATE TABLE IF NOT EXISTS contacts (
    id         INT          PRIMARY KEY AUTO_INCREMENT,
    name       VARCHAR(255) NOT NULL,
    email      VARCHAR(255) NOT NULL,
    message    TEXT         NOT NULL,
    created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- بيانات تجريبية للفعاليات
-- ============================================================
INSERT INTO events (title, description, category, location, event_date, image) VALUES
('ورشة عمل تقنية',
 'تعرف على أحدث التقنيات في عالم البرمجة وتطوير الويب. ستشمل الورشة: PHP، MySQL، JavaScript، وأدوات DevOps الحديثة.',
 'تقني', 'المدرج الرئيسي', '2025-05-20', 'event1.png'),

('مهرجان موسيقي',
 'أمسية موسيقية رائعة بمشاركة طلاب الجامعة من مختلف الكليات. ستتنوع الفقرات بين الموسيقى الكلاسيكية والشرقية والمعاصرة.',
 'موسيقي', 'المسرح الرئيسي', '2025-05-25', 'event2.png'),

('بطولة رياضية',
 'منافسات رياضية حماسية بين طلاب الكليات في كرة القدم وكرة السلة والتنس. انضم وكن جزءاً من الحماس!',
 'رياضي', 'الملعب الرئيسي', '2025-06-01', 'event3.png'),

('ندوة علمية',
 'ندوة متخصصة في مجال الذكاء الاصطناعي وتطبيقاته في التعليم. يشارك فيها نخبة من الأساتذة والباحثين.',
 'علمي', 'قاعة المؤتمرات', '2025-06-10', 'event1.png'),

('معرض الفنون',
 'معرض يجمع أعمال الطلاب الإبداعية في التصوير والرسم والنحت. فرصة ذهبية لاكتشاف المواهب الجامعية.',
 'ثقافي', 'قاعة العرض المركزية', '2025-06-15', 'event2.png');
