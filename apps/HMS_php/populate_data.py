import mysql.connector
from faker import Faker
import random

# Initialize Faker
fake = Faker()

# Connect to MySQL database
conn = mysql.connector.connect(
    host="localhost",
    port=3312,  # Update to the correct port number
    user="root",
    password="rootpassword",
    database="hospital_db"
)
cursor = conn.cursor()

# Function to truncate strings to fit within a specified length
def truncate_string(input_string, max_length):
    return input_string[:max_length]

# Function to populate patients
def populate_patients(n):
    for _ in range(n):
        sql = """
            INSERT INTO patient_info (
                full_name, DOB, weight, phone_no, address
            ) VALUES (%s, %s, %s, %s, %s)
        """
        val = (
            truncate_string(fake.name(), 20),
            random.randint(1, 100),  # Age
            random.randint(40, 100),  # Weight
            truncate_string(fake.phone_number(), 30),
            truncate_string(fake.address(), 260)
        )
        cursor.execute(sql, val)
    conn.commit()

# Function to populate appointments
def populate_appointments(n):
    for _ in range(n):
        sql = """
            INSERT INTO appointments (
                patient_id, speciality, medical_condition, doctors_suggestion, payment_amount, case_closed
            ) VALUES (%s, %s, %s, %s, %s, %s)
        """
        val = (
            random.randint(1, n),  # Assuming patient_id ranges from 1 to n
            truncate_string(random.choice(['Audiologist', 'Dentist', 'Endocrinologist', 'General Practitioner']), 30),
            truncate_string(fake.sentence(), 255),  # Adjusted to a reasonable length for medical_condition
            truncate_string(fake.sentence(), 30),  # Adjusted to fit doctors_suggestion column
            random.randint(50, 500),
            random.choice(['yes', 'no'])
        )
        cursor.execute(sql, val)
    conn.commit()

# Function to populate doctors
def populate_doctors(n):
    for _ in range(n):
        sql = """
            INSERT INTO doctors (
                email, password, fullname, speciality
            ) VALUES (%s, %s, %s, %s)
        """
        val = (
            truncate_string(fake.email(), 30),
            truncate_string(fake.password(), 30),
            truncate_string(fake.name(), 30),
            truncate_string(random.choice(['Audiologist', 'Dentist', 'Endocrinologist', 'General Practitioner']), 30)
        )
        cursor.execute(sql, val)
    conn.commit()

# Function to populate clerks
def populate_clerks(n):
    for _ in range(n):
        sql = """
            INSERT INTO clerks (
                email, password, fullname
            ) VALUES (%s, %s, %s)
        """
        val = (
            truncate_string(fake.email(), 30),
            truncate_string(fake.password(), 30),
            truncate_string(fake.name(), 30)
        )
        cursor.execute(sql, val)
    conn.commit()

# Populate the data
populate_patients(200)
populate_appointments(200)
populate_doctors(50)
populate_clerks(50)

# Close the connection
conn.close()
