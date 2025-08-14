import requests

def test_sendmail():
    url = 'http://localhost:5000/sendmail'
    data = {
        'name': 'Testname',
        'email': 'test@example.com',
        'message': 'Testnachricht'
    }
    response = requests.post(url, data=data)
    print('Status:', response.status_code)
    print('Redirected to:', response.url)

if __name__ == '__main__':
    test_sendmail()
