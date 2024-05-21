import cv2
import numpy as np
import sys
import mysql.connector

# Connect to the MySQL database
conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="x@9V*Wp$6fK2zA!",
    database="library"
)
cursor = conn.cursor()

# Create a table to store image signatures
cursor.execute('''
    CREATE TABLE IF NOT EXISTS image_signatures (
        id INT AUTO_INCREMENT PRIMARY KEY,
        image_path VARCHAR(255) UNIQUE,
        signature FLOAT
    )
''')
conn.commit()

def calculate_average_pixel(image):
    gray_image = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
    average_pixel = np.mean(gray_image)
    return average_pixel

def store_image_signature(image_path):
    img = cv2.imread(image_path)
    if img is None:
        raise ValueError(f"Unable to read image at path: {image_path}")

    avg_pixel = calculate_average_pixel(img)

    # Insert or update the signature in the database
    cursor.execute('''
        INSERT INTO image_signatures (image_path, signature)
        VALUES (%s, %s)
        ON DUPLICATE KEY UPDATE signature = %s
    ''', (image_path, avg_pixel, avg_pixel))
    conn.commit()

# Example usage for adding a new signature:
if __name__ == "__main__":
    if len(sys.argv) != 2:
        print("Usage: python3 addsign.py <image_path>")
        sys.exit(1)

    image_path_to_add = sys.argv[1]

    try:
        store_image_signature(image_path_to_add)
        print("Signature added successfully.")
    except Exception as e:
        print(f"Error occurred: {e}")
