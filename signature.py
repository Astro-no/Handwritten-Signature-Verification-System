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
    avg_pixel = calculate_average_pixel(img)

    # Insert or update the signature in the database
    cursor.execute('''
        INSERT INTO image_signatures (image_path, signature)
        VALUES (%s, %s)
        ON DUPLICATE KEY UPDATE signature = %s
    ''', (image_path, avg_pixel, avg_pixel))
    conn.commit()

def get_all_image_signatures():
    cursor.execute('''
        SELECT image_path, signature FROM image_signatures
    ''')
    results = cursor.fetchall()
    return results

def image_similarity(signature1, signature2, threshold=1):
    # Check if either signature is None
    if signature1 is None or signature2 is None:
        return False

    difference = np.abs(signature1 - signature2)

    if difference < threshold:
        return True  # Images are considered similar
    else:
        return False  # Images are considered different

def add_new_signature(image_path):
    img = cv2.imread(image_path)
    avg_pixel = calculate_average_pixel(img)

    try:
        # Try to insert the new signature into the database
        cursor.execute('''
            INSERT INTO image_signatures (image_path, signature)
            VALUES (%s, %s)
        ''', (image_path, avg_pixel))
        conn.commit()
        print("Image signature added successfully.")
    except mysql.connector.IntegrityError as e:
        if e.errno == 1062:  # Duplicate entry error
            print("Image signature already exists in the database.")
        else:
            raise  # Re-raise the exception if it's not a duplicate entry error

# Example usage:
if __name__ == "__main__":
    if len(sys.argv) != 2:
        print("Usage: python3 python_script.py <image_path>")
        sys.exit(1)

    user_image_path = sys.argv[1]

    # Debugging statement to check if the script is receiving the correct image path
    print(f"User Signature Path: {user_image_path}")

    # Calculate signature for the user-provided image without storing in the database
    img2 = cv2.imread(user_image_path)
    signature2 = calculate_average_pixel(img2)

    # Retrieve all image signatures from the database
    all_signatures = get_all_image_signatures()

    # Compare the user-provided signature with all signatures in the database
    similar_images = []
    for path, signature1 in all_signatures:
        if image_similarity(signature1, signature2):
            similar_images.append(path)

    # Display the result
    if similar_images:
        print("User-provided signature is matching with the provided signature")
        for image_path in similar_images:
            print(image_path)
    else:
        print("User-provided signature is not matching with the referenced signature")
