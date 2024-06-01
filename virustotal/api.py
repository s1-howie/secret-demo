import requests

API_KEY = '68a54a91cf7d1f0575ff055fa3e860f68ab5716269acf8ac56339c97f50fa288'

def URL_Check(api_key, url):
    url = 'https://www.virustotal.com/vtapi/v2/url/report'
    params = {'apikey': api_key, 'resource': url}
    response = requests.get(url, params=params)

    if response.status_code == 200:
        result = response.json()
        return result
    else:
        print(f'Error: {response.status_code}')
        return None
