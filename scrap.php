import time
from selenium import webdriver
from selenium.webdriver.common.by import By
from webdriver_manager.chrome import ChromeDriverManager
from selenium.webdriver.chrome.service import Service
import pandas as pd

bid, item_name, quantity, dept_det, date_start, date_end, status= [], [], [], [], [], [],[]
browser = webdriver.Chrome(service=Service(ChromeDriverManager().install()))
browser.maximize_window()
browser.get('https://bidplus.gem.gov.in/all-bids/')

time.sleep(5)


browser.find_element(By.XPATH,"//input[@id='bidrastatus'][1]").click()
browser.find_element(By.XPATH,"//input[@id='searchBid'][1]").send_keys("Materials Management")
browser.find_element(By.XPATH,"//button[@id='searchBidRA'][1]").click()



#click on date picker
browser.find_element(By.XPATH,"//input[@id='fromEndDate'][1]").click()
time.sleep(2)
browser.find_element(By.XPATH,"//select[@class='ui-datepicker-month'][1]").click()
time.sleep(2)
browser.find_element(By.XPATH,"//option[@value='3'][1]").click()
time.sleep(2)
browser.find_element(By.XPATH,"//a[normalize-space()='1'][1]").click()
time.sleep(2)





#click on date picker
browser.find_element(By.XPATH,"//input[@id='toEndDate'][1]").click()
time.sleep(2)
browser.find_element(By.XPATH,"//select[@class='ui-datepicker-month'][1]").click()
time.sleep(2)
browser.find_element(By.XPATH,"//option[@value='11'][1]").click()
time.sleep(2)
browser.find_element(By.XPATH,"//a[normalize-space()='24'][1]").click()
time.sleep(2)





while True:
    for i in range(2,12):
        bid_num=browser.find_elements(By.XPATH,"/html[1]/body[1]/div[2]/div[5]/div[2]/div["+str(i)+"]/div[1]/p[1]/a[1]")
        for bids in bid_num:
            bid.append(bids.text)
    

    startdate=browser.find_elements(By.XPATH,"//span[contains(@class,'start_date')]")

    for start in startdate:
        sd=start.text
        date_start.append(sd[:10])



    #-------------------------------------end Date-------------------------------

    enddate=browser.find_elements(By.XPATH,"//span[contains(@class,'end_date')]")


    for endd in enddate:
        ed=endd.text
        date_end.append(ed[:10])


    for i in range(2,12):
        dept=browser.find_elements(By.XPATH,"/html[1]/body[1]/div[2]/div[5]/div[2]/div["+str(i)+"]/div[3]/div[1]/div[2]/div[2]")
        for i in dept:
            dept1=i.text.replace("\n","")
            dept_det.append(dept1)


    for i in range(2,12):
        items=browser.find_elements(By.XPATH,"/html[1]/body[1]/div[2]/div[5]/div[2]/div["+str(i)+"]/div[3]/div[1]/div[1]/div[1]")
        for i in items:
            item_name.append(i.text.replace("Items: "," "))

    for i in range(2,12):
        qyt=browser.find_elements(By.XPATH,"/html[1]/body[1]/div[2]/div[5]/div[2]/div["+str(i)+"]/div[3]/div[1]/div[1]/div[2]")
        for i in qyt:
            quantity.append(i.text.replace("Quantity: ",""))

    stat=browser.find_elements(By.XPATH,"//span [contains(@class,'text-success')]")
    for i in stat:
        status.append(i.text)





    Biddata={
        "Bid Number":bid,
        "Item Name":item_name,
        "Item Qyt":quantity,
        "Depatment ":dept_det,
        "Start Date":date_start,
        "End Date":date_end,
        "Bid Status":status

    }
    DataFrame = pd.DataFrame(Biddata)
    DataFrame.to_csv("bid.csv")


    browser.find_element(By.XPATH,"//a[contains(@class,'page-link next')]").click()

    browser.implicitly_wait(10)
