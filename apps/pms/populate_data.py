import mysql.connector
from faker import Faker
import random

# Initialize Faker
fake = Faker()

# Connect to MySQL database
conn = mysql.connector.connect(
    host="localhost",
    port=3313,  # Use the correct port number
    user="root",
    password="rootpassword",
    database="pms_db"
)
cursor = conn.cursor()

# Function to truncate strings to fit within a specified length
def truncate_string(input_string, max_length):
    return input_string[:max_length]

# Function to populate patients
def populate_patients(n):
    for _ in range(n):
        sql = """
            INSERT INTO patients (
                patient_name, address, cnic, date_of_birth, phone_number, gender
            ) VALUES (%s, %s, %s, %s, %s, %s)
        """
        val = (
            truncate_string(fake.name(), 60),
            truncate_string(fake.address(), 100),
            truncate_string(fake.ssn(), 17),
            fake.date_of_birth(),
            truncate_string(fake.phone_number(), 12),
            random.choice(['Male', 'Female'])
        )
        cursor.execute(sql, val)
    conn.commit()

# Function to populate patient visits
def populate_patient_visits(n):
    for _ in range(n):
        sql = """
            INSERT INTO patient_visits (
                visit_date, next_visit_date, bp, weight, disease, patient_id
            ) VALUES (%s, %s, %s, %s, %s, %s)
        """
        val = (
            fake.date_this_year(),
            fake.date_this_year(),
            truncate_string(f"{random.randint(90, 140)}/{random.randint(60, 90)}", 23),
            truncate_string(f"{random.randint(50, 100)} kg", 12),
            truncate_string(fake.word(), 30),
            random.randint(1, n)  # Assuming patient_id ranges from 1 to n
        )
        cursor.execute(sql, val)
    conn.commit()

# Function to populate patient medication history
def populate_patient_medication_history(n):
    for _ in range(n):
        sql = """
            INSERT INTO patient_medication_history (
                patient_visit_id, medicine_details_id, quantity, dosage
            ) VALUES (%s, %s, %s, %s)
        """
        val = (
            random.randint(1, n),  # Assuming patient_visit_id ranges from 1 to n
            random.randint(1, 7),  # Assuming medicine_details_id ranges from 1 to 7
            random.randint(1, 5),
            truncate_string(f"{random.randint(100, 500)} mg", 20)
        )
        cursor.execute(sql, val)
    conn.commit()

# Populate the data
populate_patients(200)
populate_patient_visits(200)
populate_patient_medication_history(200)

# Close the connection
conn.close()
