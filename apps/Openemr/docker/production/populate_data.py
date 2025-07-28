import mysql.connector
from faker import Faker

fake = Faker()

# Database connection
db = mysql.connector.connect(
    host="10.0.0.2",  # Update this to match the actual IP or hostname of your database container
    port=3306,  # Ensure this matches the exposed port in your docker-compose file
    user="openemr",
    password="openemr",
    database="openemr"
)

cursor = db.cursor()

# Function to insert fake data into the `patient_data` table
def insert_patients():
    for _ in range(100):
        cursor.execute("""
            INSERT INTO patient_data (pid, lname, fname, mname, DOB, sex, ss, drivers_license, status, contact_relationship, phone_home, phone_biz, phone_contact, email, zip, city, state, country_code, occupation, employer)
            VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
        """, (
            None,
            fake.last_name(),
            fake.first_name(),
            fake.first_name(),
            fake.date_of_birth(),
            fake.random_element(elements=('M', 'F')),
            fake.ssn(),
            fake.license_plate(),
            fake.random_element(elements=('single', 'married', 'divorced')),
            fake.name(),
            fake.phone_number(),
            fake.phone_number(),
            fake.phone_number(),
            fake.email(),
            fake.zipcode(),
            fake.city(),
            fake.state(),
            fake.country_code(),
            fake.job(),
            fake.company()
        ))
    db.commit()

# Call the function to populate data
insert_patients()

# Close the cursor and database connection
cursor.close()
db.close()

print("Fake data inserted successfully.")
