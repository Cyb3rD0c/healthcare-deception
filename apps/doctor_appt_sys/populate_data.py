import mysql.connector
from faker import Faker

fake = Faker()

conn = mysql.connector.connect(
    host="doc_appt_sys_db",  # Ensure this matches the service name in docker-compose.yml
    user="root",
    password="root",
    database="sourcecodester_dadb"
)

cursor = conn.cursor()

# Example query to insert fake data into the patient table
for _ in range(10):
    cursor.execute("""
        INSERT INTO patient (patientFirstName, patientLastName, patientGender, patientPhoneNumber, patientAddress, patientDOB, patientMaritialStatus)
        VALUES (%s, %s, %s, %s, %s, %s, %s)
    """, (
        fake.first_name(),
        fake.last_name(),
        fake.random_element(elements=('Male', 'Female')),
        fake.phone_number(),
        fake.address(),
        fake.date_of_birth(),
        fake.random_element(elements=('Single', 'Married', 'Divorced', 'Widowed'))
    ))

conn.commit()
cursor.close()
conn.close()
