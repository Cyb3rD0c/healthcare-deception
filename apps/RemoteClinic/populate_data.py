import mysql.connector
from faker import Faker
import random

# Initialize Faker
fake = Faker()

# Connect to MySQL database
conn = mysql.connector.connect(
    host="localhost",
    port=3316,  # Use the correct port number
    user="remoteclinic_user",
    password="remoteclinic_password",
    database="remoteclinic_db"
)
cursor = conn.cursor()

# Function to truncate strings to fit within a specified length
def truncate_string(input_string, max_length):
    return input_string[:max_length]

# Function to populate patients
def populate_patients(n):
    for _ in range(n):
        sql = """
            INSERT INTO p_patients_dir (
                gender, age, serial, name, contact, email, weight, profession, ref_contact, address, branch, physician, last_update, friendly_name
            ) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
        """
        val = (
            random.choice(['Male', 'Female']),
            str(random.randint(1, 100)),
            'PA',
            truncate_string(fake.name(), 250),
            'n/a',
            'n/a',
            truncate_string(str(fake.random_int(min=50, max=100)), 250),
            truncate_string(fake.job(), 250),
            'n/a',
            truncate_string(fake.address(), 250),
            random.randint(1, 10),  # Example branch IDs
            random.randint(1, 10),  # Example physician IDs
            fake.date_time_this_year(),
            truncate_string(fake.first_name(), 250)
        )
        cursor.execute(sql, val)
    conn.commit()

# Function to populate reports
def populate_reports(n):
    for _ in range(n):
        sql = """
            INSERT INTO p_reports (
                patient, charge, charging_for, fever, blood_pressure, symptoms, attachement, composed_by, engaged_by, signed_by, notes, reply, last_update, branch, checkout_charges, cc
            ) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
        """
        val = (
            truncate_string(fake.name(), 250),
            truncate_string(str(fake.random_int(min=100, max=1000)), 250),
            'n/a',
            truncate_string(str(fake.random_int(min=98, max=105)), 250),
            truncate_string(f"{random.randint(90, 140)}/{random.randint(60, 90)}", 250),
            truncate_string(fake.text(), 250),
            '0',
            random.randint(1, 10),  # Example composed_by IDs
            random.randint(1, 10),  # Example engaged_by IDs
            random.randint(1, 10),  # Example signed_by IDs
            truncate_string(fake.text(), 250),
            truncate_string(fake.text(), 250),
            fake.date_time_this_year(),
            random.randint(1, 10),  # Example branch IDs
            truncate_string(str(fake.random_int(min=100, max=1000)), 250),
            truncate_string(str(fake.random_int(min=100, max=1000)), 250)
        )
        cursor.execute(sql, val)
    conn.commit()

# Function to populate logs
def populate_logs(n):
    for _ in range(n):
        sql = """
            INSERT INTO p_logs (
                user, at, action, type, priority
            ) VALUES (%s, %s, %s, %s, %s)
        """
        val = (
            str(random.randint(1, 10)),  # Example user IDs
            fake.date_time_this_year(),
            truncate_string(fake.text(), 250),
            truncate_string(fake.word(), 250),
            truncate_string(str(random.randint(1, 100)), 250)
        )
        cursor.execute(sql, val)
    conn.commit()

# Populate the data
populate_patients(200)
populate_reports(200)
populate_logs(200)

# Close the connection
conn.close()
