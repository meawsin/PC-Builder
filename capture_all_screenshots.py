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

def login_as(username, password, expected_target):
    driver.get(f"{BASE_URL}/logout.php")
    time.sleep(1)
    driver.get(f"{BASE_URL}/login.php")
    time.sleep(1)
    user_input = driver.find_element(By.ID, "user")
    pass_input = driver.find_element(By.ID, "pass")
    user_input.clear()
    user_input.send_keys(username)
    pass_input.clear()
    pass_input.send_keys(password)
    driver.find_element(By.ID, "btn").click()
    
    for _ in range(15):
        time.sleep(0.5)
        if expected_target in driver.current_url:
            break
    print(f"Logged in as {username}. Current URL: {driver.current_url}")

try:
    print("1. Capturing Home Page...")
    driver.get(f"{BASE_URL}/index.php")
    time.sleep(1)
    driver.save_screenshot(os.path.join(OUTPUT_DIR, "01_home_page.png"))

    print("2. Capturing Login Page...")
    driver.get(f"{BASE_URL}/login.php")
    time.sleep(0.5)
    driver.save_screenshot(os.path.join(OUTPUT_DIR, "02_user_login.png"))

    print("3. Logging in as User Mohsin...")
    login_as("Mohsin", "mohsin", "index.php")

    print("4. Capturing PC Builder...")
    driver.get(f"{BASE_URL}/building.php")
    time.sleep(1)
    driver.save_screenshot(os.path.join(OUTPUT_DIR, "03_pc_builder.png"))

    print("5. Capturing User Builds (mybuilds.php)...")
    driver.get(f"{BASE_URL}/mybuilds.php")
    time.sleep(1)
    driver.save_screenshot(os.path.join(OUTPUT_DIR, "04_my_builds.png"))

    print("6. Capturing Order Status (orderStatus.php)...")
    driver.get(f"{BASE_URL}/orderStatus.php")
    time.sleep(1)
    driver.save_screenshot(os.path.join(OUTPUT_DIR, "05_order_status.png"))

    print("7. Capturing Order Invoice / Details (printbuild.php)...")
    driver.get(f"{BASE_URL}/printbuild.php?order_id=2")
    time.sleep(1)
    driver.save_screenshot(os.path.join(OUTPUT_DIR, "06_order_invoice.png"))

    print("8. Capturing Component Catalog...")
    driver.get(f"{BASE_URL}/components.php?component=processors")
    time.sleep(1)
    driver.save_screenshot(os.path.join(OUTPUT_DIR, "07_component_catalog.png"))

    print("9. Capturing Product Comparison...")
    driver.get(f"{BASE_URL}/compare.php?category=graphics_card&product1=GTX004&product2=GTX010")
    time.sleep(1)
    driver.save_screenshot(os.path.join(OUTPUT_DIR, "08_product_comparison.png"))

    print("10. Capturing Ask the Expert Q&A...")
    driver.get(f"{BASE_URL}/ask.php")
    time.sleep(1)
    driver.save_screenshot(os.path.join(OUTPUT_DIR, "09_ask_expert.png"))

    print("11. Logging in as Admin John Doe...")
    login_as("John Doe", "password123", "admin_dashboard.php")

    print("12. Capturing Admin Dashboard...")
    driver.get(f"{BASE_URL}/admin_dashboard.php")
    time.sleep(1)
    driver.save_screenshot(os.path.join(OUTPUT_DIR, "10_admin_dashboard.png"))

    print("13. Capturing DBMS Analytics & Insights...")
    driver.get(f"{BASE_URL}/Anlytics.php")
    time.sleep(1)
    driver.save_screenshot(os.path.join(OUTPUT_DIR, "11_dbms_analytics.png"))

    print("14. Capturing Admin Manage Orders...")
    driver.get(f"{BASE_URL}/manage_orders.php")
    time.sleep(1)
    driver.save_screenshot(os.path.join(OUTPUT_DIR, "12_manage_orders.png"))

    print("15. Capturing Admin Manage Products...")
    driver.get(f"{BASE_URL}/manage_product.php?category=CPU")
    time.sleep(1)
    driver.save_screenshot(os.path.join(OUTPUT_DIR, "13_manage_products.png"))

    print("16. Capturing Admin Manage Users...")
    driver.get(f"{BASE_URL}/manage_users.php")
    time.sleep(1)
    driver.save_screenshot(os.path.join(OUTPUT_DIR, "14_manage_users.png"))

    print("17. Capturing Admin Reply to Chats...")
    driver.get(f"{BASE_URL}/admin_responses.php")
    time.sleep(1)
    driver.save_screenshot(os.path.join(OUTPUT_DIR, "15_admin_responses.png"))

    print(f"\nAll screenshots successfully saved to: {OUTPUT_DIR}")

finally:
    driver.quit()
