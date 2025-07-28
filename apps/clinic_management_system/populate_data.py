import mysql.connector
from faker import Faker

fake = Faker()

# Database connection
conn = mysql.connector.connect(
    host='localhost',  # Assuming you run this on the host machine
    port=3310,  # Port as defined in the docker-compose.yml
    user='root',
    password='rootpassword',
    database='clinic_db'
)
cursor = conn.cursor()

def populate_admin(n):
    for _ in range(n):
        sql = "INSERT INTO admin (username, loginid, password, fname, lname, gender, dob, mobileno, addr, notes, image, created_on, updated_on, role_id, last_login, delete_status) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"
        val = (fake.user_name(), fake.email()[:30], fake.sha256(), fake.first_name(), fake.last_name(), fake.random_element(elements=('Male', 'Female')), fake.date(), fake.phone_number()[:15], fake.address(), fake.text(), 'profile.jpg', fake.date(), fake.date(), 1, fake.date(), 0)
        cursor.execute(sql, val)
    conn.commit()

def populate_patients(n):
    for _ in range(n):
        sql = "INSERT INTO patient (patientname, admissiondate, admissiontime, address, mobileno, city, pincode, loginid, password, bloodgroup, gender, dob, status, delete_status) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"
        val = (fake.name(), fake.date(), fake.time(), fake.address(), fake.phone_number()[:15], fake.city(), fake.zipcode(), fake.email()[:50], fake.sha256(), fake.random_element(elements=('A+', 'B+', 'AB+', 'O+')), fake.random_element(elements=('Male', 'Female')), fake.date(), 'Active', 0)
        cursor.execute(sql, val)
    conn.commit()

def populate_appointments(n):
    for _ in range(n):
        sql = "INSERT INTO appointment (appointmenttype, patientid, roomid, departmentid, appointmentdate, appointmenttime, doctorid, status, app_reason, delete_status) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"
        val = (fake.random_element(elements=('Consultation', 'Follow-up', 'Emergency')), fake.random_int(min=1, max=n), fake.random_int(min=1, max=5), fake.random_int(min=1, max=2), fake.date(), fake.time(), fake.random_int(min=1, max=5), fake.random_element(elements=('Approved', 'Active', 'Inactive')), fake.text(), 0)
        cursor.execute(sql, val)
    conn.commit()

def populate_doctors(n):
    for _ in range(n):
        sql = "INSERT INTO doctor (doctorname, mobileno, departmentid, loginid, password, status, education, experience, consultancy_charge, delete_status) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"
        val = (fake.name(), fake.phone_number()[:15], fake.random_int(min=1, max=2), fake.email()[:25], fake.sha256(), 'Active', fake.random_element(elements=('MD', 'DO', 'MBBS')), fake.random_int(min=1, max=30), fake.random_int(min=100, max=1000), 0)
        cursor.execute(sql, val)
    conn.commit()

# Populate data
populate_admin(10)
populate_patients(200)
populate_appointments(200)
populate_doctors(50)

cursor.close()
conn.close()

print("Data population complete.")
