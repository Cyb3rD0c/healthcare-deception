import mysql.connector
from faker import Faker
import random

# Initialize Faker
fake = Faker()

# Connect to MySQL database
conn = mysql.connector.connect(
    host="localhost",
    port=3314,  # Update to the correct port number
    user="root",
    password="rootpassword",
    database="edoc"
)
cursor = conn.cursor()

# Function to populate patients
def populate_patients(n):
    for _ in range(n):
        sql = "INSERT INTO patient (pemail, pname, ppassword, paddress, pnic, pdob, ptel) VALUES (%s, %s, %s, %s, %s, %s, %s)"
        val = (
            fake.email(),
            fake.name(),
            fake.password(),
            fake.address(),
            fake.unique.random_number(digits=10),
            fake.date_of_birth(),
            fake.phone_number()[:15]  # Ensure phone number length
        )
        cursor.execute(sql, val)
    conn.commit()

# Function to populate doctors
def populate_doctors(n):
    for _ in range(n):
        sql = "INSERT INTO doctor (docemail, docname, docpassword, docnic, doctel, specialties) VALUES (%s, %s, %s, %s, %s, %s)"
        val = (
            fake.email(),
            fake.name(),
            fake.password(),
            fake.unique.random_number(digits=10),
            fake.phone_number()[:15],  # Ensure phone number length
            fake.random_int(min=1, max=56)  # Assuming specialties ID range from 1 to 56
        )
        cursor.execute(sql, val)
    conn.commit()

# Function to populate schedules
def populate_schedules(n):
    cursor.execute("SELECT docid FROM doctor")
    doctor_ids = [row[0] for row in cursor.fetchall()]
    for _ in range(n):
        sql = "INSERT INTO schedule (docid, title, scheduledate, scheduletime, nop) VALUES (%s, %s, %s, %s, %s)"
        val = (
            random.choice(doctor_ids),
            fake.sentence(nb_words=3),
            fake.date_this_year(),
            fake.time(),
            fake.random_int(min=1, max=50)
        )
        cursor.execute(sql, val)
    conn.commit()

# Function to populate appointments
def populate_appointments(n):
    cursor.execute("SELECT pid FROM patient")
    patient_ids = [row[0] for row in cursor.fetchall()]
    cursor.execute("SELECT scheduleid FROM schedule")
    schedule_ids = [row[0] for row in cursor.fetchall()]
    for _ in range(n):
        sql = "INSERT INTO appointment (pid, apponum, scheduleid, appodate) VALUES (%s, %s, %s, %s)"
        val = (
            random.choice(patient_ids),
            fake.random_int(min=1, max=100),
            random.choice(schedule_ids),
            fake.date_this_year()
        )
        cursor.execute(sql, val)
    conn.commit()

# Populate the data
populate_patients(200)
populate_doctors(50)
populate_schedules(100)
populate_appointments(200)

# Close the connection
conn.close()
