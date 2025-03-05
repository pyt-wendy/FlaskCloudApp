from flask import Flask
app = Flask(__name__)
@app.route("/")
def hello():
 return "Hello, This is Queen Akinyi!"
if __name__ == "__main__":
 app.run(debug=True)
