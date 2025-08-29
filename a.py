import requests

url = "https://investidor10.com.br/api/cotacao/fii/59"

params = {
    'ticker': 'VGIR11',
    'type': '1',
    'currences[]': '1'
}

response = requests.get(url, params=params)

# data = response.json()
# print(data['price'])
# print(data['last_update'])

print("Status code:", response.status_code)
print("Resposta bruta:", response.text)  # V