import time
import platform
import psutil
import requests

SERVER_URL = "http://192.168.0.114/fynexi-monitoring/api/report.php"

DEVICE_ID = 3

INTERVAL = 5


def get_storage_usage():
    disk = psutil.disk_usage("C:\\")
    return disk.percent


def send_monitoring_data():

    cpu = psutil.cpu_percent(interval=1)

    ram = psutil.virtual_memory().percent

    storage = get_storage_usage()

    data = {
        "device_id": DEVICE_ID,
        "cpu_usage": cpu,
        "ram_usage": ram,
        "storage_usage": storage
    }

    try:

        response = requests.post(
            SERVER_URL,
            data=data,
            timeout=5
        )

        print("================================")
        print("Fynexi Client Agent")
        print("================================")
        print("Device ID :", DEVICE_ID)
        print("Hostname  :", platform.node())
        print("CPU       :", cpu, "%")
        print("RAM       :", ram, "%")
        print("Storage   :", storage, "%")
        print("Server    :", response.text)

    except Exception as error:

        print("Gagal menghubungi server:")
        print(error)


while True:

    send_monitoring_data()

    time.sleep(INTERVAL)