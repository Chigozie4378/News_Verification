from flask import Flask, request, jsonify
from flask_cors import CORS
from joblib import load
import subprocess

app = Flask(__name__)
CORS(app)

@app.route('/predict', methods=['POST'])
def predict():
    data = request.get_json()
    news = data['news']  # get the news from the received JSON data

    # Load the trained models
    category_clf = load('../../model/category_clf.joblib')
    clf = load('../../model/clf.joblib')
    vectorizer = load('../../model/vectorizer.joblib')

    vectorized_news = vectorizer.transform([news])
    
    category = category_clf.predict(vectorized_news)[0]
    prediction = clf.predict(vectorized_news)[0]
    print("Prediction: ", prediction)
    print("Type of prediction: ", type(prediction))

    is_real = bool(prediction == 1)

    return jsonify({
        'category': category,
        'is_real': is_real
    })


@app.route('/train_logistic', methods=['POST'])
def train_logistic():
    # Run the training script and capture the output
    result = subprocess.run(['python', '../model_training/train_logistic.py'], capture_output=True, text=True)

    if result.returncode != 0:
        # Something went wrong
        return jsonify(success=False, message=result.stderr), 500

    # Extract the accuracy from the output
    lines = result.stdout.split('\n')
    category_accuracy = float(lines[0].split(': ')[1])
    model_accuracy = float(lines[1].split(': ')[1])

    return jsonify(success=True, category_accuracy=category_accuracy, model_accuracy=model_accuracy)

# Train Decision Tree
@app.route('/train_decision_tree', methods=['POST'])
def train_decision_tree():
    # Run the training script and capture the output
    result = subprocess.run(['python', '../model_training/train_decision_tree.py'], capture_output=True, text=True)

    if result.returncode != 0:
        # Something went wrong
        return jsonify(success=False, message=result.stderr), 500

    # Extract the accuracy from the output
    lines = result.stdout.split('\n')
    category_accuracy = float(lines[0].split(': ')[1])
    model_accuracy = float(lines[1].split(': ')[1])

    return jsonify(success=True, category_accuracy=category_accuracy, model_accuracy=model_accuracy)

# Train Random Forest
@app.route('/train_random_forest', methods=['POST'])
def train_random_forest():
    # Run the training script and capture the output
    result = subprocess.run(['python', '../model_training/train_random_forest.py'], capture_output=True, text=True)

    if result.returncode != 0:
        # Something went wrong
        return jsonify(success=False, message=result.stderr), 500

    # Extract the accuracy from the output
    lines = result.stdout.split('\n')
    category_accuracy = float(lines[0].split(': ')[1])
    model_accuracy = float(lines[1].split(': ')[1])

    return jsonify(success=True, category_accuracy=category_accuracy, model_accuracy=model_accuracy)

# Train Support Vector
@app.route('/train_support_vector', methods=['POST'])
def train_support_vector():
    # Run the training script and capture the output
    result = subprocess.run(['python', '../model_training/train_support_vector.py'], capture_output=True, text=True)

    if result.returncode != 0:
        # Something went wrong
        return jsonify(success=False, message=result.stderr), 500

    # Extract the accuracy from the output
    lines = result.stdout.split('\n')
    category_accuracy = float(lines[0].split(': ')[1])    
    model_accuracy = float(lines[1].split(': ')[1])
    return jsonify(success=True, category_accuracy=category_accuracy, model_accuracy=model_accuracy)

@app.route('/logs', methods=['GET'])
def logs():
    with open('../../model/training.log', 'r', encoding="utf-8") as f:
        return f.read()


if __name__ == '__main__':
    app.run(port=5000)