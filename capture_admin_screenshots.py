import os
import time
from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.common.by import By
from webdriver_manager.chrome import ChromeDriverManager

BASE_URL = "http://localhost:8080"
OUTPUT_DIR = os.path.abspath("screenshots")
os.makedirs(OUTPUT_DIR, exist_ok=True)

chrome_options = Options()
chrome_options.add_argument("--headless=new")
chrome_options.add_argument("--disable-gpu")
chrome_options.add_argument("--window-size=1440,960")
chrome_options.add_argument("--no-sandbox")
chrome_options.add_argument("--force-device-scale-factor=1")

service = Service(ChromeDriverManager().install())
driver = webdriver.Chrome(service=service, options=chrome_options)

try:
    print("Logging into Admin portal...")
    driver.get(f"{BASE_URL}/login.php")
    time.sleep(1)
    driver.find_element(By.ID, "user").send_keys("John Doe")
    driver.find_element(By.ID, "pass").send_keys("password123")
    driver.find_element(By.ID, "btn").click()
    time.sleep(2)
    print("Admin login URL:", driver.current_url)

    print("10. Capturing Admin Dashboard...")
    driver.get(f"{BASE_URL}/admin_dashboard.php")
    time.sleep(1)
    driver.save_screenshot(os.path.join(OUTPUT_DIR, "10_admin_dashboard.png"))

    print("11. Capturing DBMS Analytics & Insights...")
    driver.get(f"{BASE_URL}/Anlytics.php")
    time.sleep(1)
    driver.save_screenshot(os.path.join(OUTPUT_DIR, "11_dbms_analytics.png"))

    print("12. Capturing Admin Manage Orders...")
    driver.get(f"{BASE_URL}/manage_orders.php")
    time.sleep(1)
    driver.save_screenshot(os.path.join(OUTPUT_DIR, "12_manage_orders.png"))

    print("13. Capturing Admin Manage Products...")
    driver.get(f"{BASE_URL}/manage_product.php?category=CPU")
    time.sleep(1)
    driver.save_screenshot(os.path.join(OUTPUT_DIR, "13_manage_products.png"))

    print("14. Capturing Admin Manage Users...")
    driver.get(f"{BASE_URL}/manage_users.php")
    time.sleep(1)
    driver.save_screenshot(os.path.join(OUTPUT_DIR, "14_manage_users.png"))

    print("15. Capturing Admin Responses / Chat...")
    driver.get(f"{BASE_URL}/admin_responses.php")
    time.sleep(1)
    driver.save_screenshot(os.path.join(OUTPUT_DIR, "15_admin_responses.png"))

    print("Admin screenshots captured successfully!")
finally:
    driver.quit()
