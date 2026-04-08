from flask import Flask, request, jsonify
from escpos.printer import Win32Raw

print("SERVICE AKTIF DARI FILE INI")


app = Flask(__name__)

@app.route('/')
def home():
    return "Service OK"

@app.route('/print', methods=['POST'])
def print_data():
    try:
        text = request.json.get('text', '')

        p = Win32Raw("POS-80")  # ganti sesuai nama printer

        p.text(text)
        p.cut()

        return jsonify({"status": "success"})

    except Exception as e:
        return jsonify({"status": "error", "msg": str(e)})

if __name__ == '__main__':
    app.run(port=5000)
