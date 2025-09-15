# Staff Training
BSBCRT611, BSBTWK502, BSBXCS402 Assessment v1.0

## DB
CREATE TABLE staff (
    uuid UUID PRIMARY KEY DEFAULT UUID(),
    username VARCHAR(64) NOT NULL,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    password_hash VARCHAR(255) NOT NULL,
    UNIQUE ('username')
);

CREATE TABLE class (
    uuid UUID PRIMARY KEY DEFAULT UUID(),
    class VARCHAR(50) NOT NULL,
    start_date DATE,
    end_date DATE
);

CREATE TABLE enrolments (
    uuid UUID PRIMARY KEY DEFAULT UUID(),
    staff_uid UUID,
    class_uid UUID,
    pre_quiz TINYINT(1) NOT NULL DEFAULT 0,
    training TINYINT(1) NOT NULL DEFAULT 0,
    post_quiz TINYINT(1) NOT NULL DEFAULT 0,
    FOREIGN KEY (staff_uid) REFERENCES staff (uuid) ON DELETE CASCADE,
    FOREIGN KEY (class_uid) REFERENCES class (uuid) ON DELETE CASCADE,
    UNIQUE ('staff_uid', 'class_uid')
);
