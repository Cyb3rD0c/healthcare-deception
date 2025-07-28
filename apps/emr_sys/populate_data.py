import mysql.connector
from faker import Faker
import random

# Initialize Faker
fake = Faker()

# Connect to MySQL database
conn = mysql.connector.connect(
    host="localhost",
    port=3315,  # Update to the correct port number
    user="emr_user",
    password="emr_password",
    database="clinic"
)
cursor = conn.cursor()

# Function to populate patients
def populate_patients(n):
    for _ in range(n):
        sql = """
            INSERT INTO tbl_petients (
                Mtitle, Firstname, Middlename, Sirname, Gender, Phone, NextKphone, DOB, Location, Relation, Guardian, Status, Status2, Date, Payment
            ) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
        """
        val = (
            random.choice(['Mr', 'Mrs', 'Miss']),
            fake.first_name(),
            fake.first_name(),
            fake.last_name(),
            random.choice(['Male', 'Female']),
            fake.phone_number()[:30],
            fake.phone_number()[:30],
            fake.date_of_birth(),
            fake.address(),
            fake.random_element(elements=("Brother", "Sister", "Mother", "Father", "Wife", "Husband")),
            fake.name(),
            fake.random_element(elements=("Treated", "Pharmacy", "Consultation", "Admission")),
            fake.random_element(elements=("", "Treated", "Admission")),
            fake.date_this_decade(),
            fake.random_element(elements=("CASH", "SCHEME"))
        )
        cursor.execute(sql, val)
    conn.commit()

# Function to populate drugs
def populate_drugs(n):
    for _ in range(n):
        sql = """
            INSERT INTO tbl_drugs (
                Name, DOE, Quantity, Drugsremain, PurchasedPrice, RetailPrice, Strength, Medstype, Marker
            ) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)
        """
        val = (
            fake.word(),
            fake.date_this_decade(),
            random.randint(100, 10000),
            random.randint(10, 5000),
            random.uniform(1, 100),
            random.uniform(1, 200),
            fake.random_number(digits=3),
            random.choice(['Tablet', 'Capsule', 'Liquid', 'Injection']),
            random.uniform(1, 10)
        )
        cursor.execute(sql, val)
    conn.commit()

# Function to populate laboratory results
def populate_laboratory_results(n):
    for _ in range(n):
        sql = """
            INSERT INTO tbl_laboratory (
                Patientid, Diseased, Test_RBS, Test_FBS, Test_PBS, Test_UCT, Test_MRDT, Test_FBC, Test_TFT, Test_LFT, Patient_Complaint, Patient_Story, Test_comment, Results, Officer, Date, Status
            ) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
        """
        val = (
            random.randint(1, 100),
            fake.word(),
            fake.random_number(digits=2),
            fake.random_number(digits=2),
            fake.random_number(digits=2),
            fake.random_number(digits=2),
            fake.random_number(digits=2),
            fake.random_number(digits=2),
            fake.random_number(digits=2),
            fake.random_number(digits=2),
            fake.sentence(),
            fake.sentence(),
            fake.sentence(),
            fake.sentence(),
            fake.name(),
            fake.date_this_decade(),
            fake.random_element(elements=("Closed", "Open"))
        )
        cursor.execute(sql, val)
    conn.commit()

# Function to populate transactions
def populate_transactions(n):
    for _ in range(n):
        sql = """
            INSERT INTO tbl_transactions (
                Patientid, Drugname, Quantity, Amount, Days, Unitprice, Totalcost, Consultation_fee, Lab_fee, Payment, Scheme_id, Date, Time
            ) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
        """
        val = (
            random.randint(1, 100),
            fake.word(),
            fake.random_number(digits=2),
            random.uniform(1, 100),
            random.randint(1, 30),
            random.uniform(1, 100),
            random.uniform(10, 1000),
            random.uniform(10, 500),
            random.uniform(10, 500),
            random.choice(['CASH', 'SCHEME']),
            fake.random_number(digits=6),
            fake.date_this_decade(),
            fake.time()
        )
        cursor.execute(sql, val)
    conn.commit()

# Populate the data
populate_patients(200)
populate_drugs(100)
populate_laboratory_results(200)
populate_transactions(200)

# Close the connection
conn.close()
