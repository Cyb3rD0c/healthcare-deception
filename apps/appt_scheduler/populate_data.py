from faker import Faker
import mysql.connector

# Initialize Faker
fake = Faker()

# Database connection
conn = mysql.connector.connect(
    host='localhost',  # Host for the MariaDB service
    port=3311,  # Port for the MariaDB service
    user='root',  # Database user
    password='rootpassword',  # Database password
    database='scheduler_db'  # Database name
)

cursor = conn.cursor()

# Function to generate fake data and insert into appointments
def populate_appointments(num_records):
    for _ in range(num_records):
        patient_id = fake.random_int(min=1, max=200)
        date_sched = fake.date_time_this_year()
        ailment = fake.text(max_nb_chars=50)
        status = fake.random_element(elements=(0, 1))
        date_created = fake.date_time_this_year()
        
        sql = "INSERT INTO appointments (patient_id, date_sched, ailment, status, date_created) VALUES (%s, %s, %s, %s, %s)"
        val = (patient_id, date_sched, ailment, status, date_created)
        cursor.execute(sql, val)
    
    conn.commit()

# Function to generate fake data and insert into location
def populate_location(num_records):
    for _ in range(num_records):
        location = fake.address()
        description = fake.text(max_nb_chars=200)
        max_a_day = fake.random_int(min=1, max=500)
        date_created = fake.date_time_this_year()
        date_updated = fake.date_time_this_year()
        
        sql = "INSERT INTO location (location, description, max_a_day, date_created, date_updated) VALUES (%s, %s, %s, %s, %s)"
        val = (location, description, max_a_day, date_created, date_updated)
        cursor.execute(sql, val)
    
    conn.commit()

# Function to generate fake data and insert into patient_list
def populate_patient_list(num_records):
    patient_ids = []
    for _ in range(num_records):
        name = fake.name()
        date_created = fake.date_time_this_year()
        
        sql = "INSERT INTO patient_list (name, date_created) VALUES (%s, %s)"
        val = (name, date_created)
        cursor.execute(sql, val)
        patient_ids.append(cursor.lastrowid)
    
    conn.commit()
    return patient_ids

# Function to generate fake data and insert into patient_meta
def populate_patient_meta(patient_ids):
    for patient_id in patient_ids:
        meta_fields = ['email', 'contact', 'gender', 'dob', 'address']
        for field in meta_fields:
            if field == 'email':
                meta_value = fake.email()
            elif field == 'contact':
                meta_value = fake.phone_number()
            elif field == 'gender':
                meta_value = fake.random_element(elements=('Male', 'Female'))
            elif field == 'dob':
                meta_value = fake.date_of_birth()
            elif field == 'address':
                meta_value = fake.address()
            
            date_created = fake.date_time_this_year()
            sql = "INSERT INTO patient_meta (patient_id, meta_field, meta_value, date_created) VALUES (%s, %s, %s, %s)"
            val = (patient_id, field, meta_value, date_created)
            cursor.execute(sql, val)
    
    conn.commit()

# Function to generate fake data and insert into users
def populate_users(num_records):
    for _ in range(num_records):
        firstname = fake.first_name()
        lastname = fake.last_name()
        username = fake.user_name()
        password = fake.password()
        avatar = fake.image_url()
        last_login = fake.date_time_this_year()
        user_type = fake.random_element(elements=(0, 1))
        date_added = fake.date_time_this_year()
        date_updated = fake.date_time_this_year()
        
        sql = "INSERT INTO users (firstname, lastname, username, password, avatar, last_login, type, date_added, date_updated) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)"
        val = (firstname, lastname, username, password, avatar, last_login, user_type, date_added, date_updated)
        cursor.execute(sql, val)
    
    conn.commit()

# Populate patient_list and get the patient_ids
patient_ids = populate_patient_list(200)

# Populate patient_meta with valid patient_ids
populate_patient_meta(patient_ids)

# Populate the other tables
populate_appointments(200)
populate_location(20)  # Assuming fewer locations
populate_users(50)  # Assuming fewer users

# Close the connection
cursor.close()
conn.close()
