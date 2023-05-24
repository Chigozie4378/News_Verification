import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.ensemble import RandomForestClassifier
from sklearn.metrics import accuracy_score
from joblib import dump
import logging

# Set up logging
logging.basicConfig(filename='../../model/training.log', level=logging.INFO)

# Load the data
data = pd.read_csv("../../datasets/ds3.csv")
data = data.dropna()

# Separate the features and the labels
X = data['text']
y_category = data['subject']
y = data['label']

# Split the data into training and test sets for each task
X_train_category, X_test_category, y_train_category, y_test_category = train_test_split(X, y_category, test_size=0.2, random_state=42)
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# Vectorize the text data
vectorizer = TfidfVectorizer(stop_words="english", max_df=0.7)
X_train_vectorized_category = vectorizer.fit_transform(X_train_category)
X_test_vectorized_category = vectorizer.transform(X_test_category)
X_train_vectorized = vectorizer.transform(X_train)
X_test_vectorized = vectorizer.transform(X_test)

# Train the category classifier
category_clf = RandomForestClassifier(n_estimators=100)
category_clf.fit(X_train_vectorized_category, y_train_category)

# Train the classifier
clf = RandomForestClassifier(n_estimators=100)
clf.fit(X_train_vectorized, y_train)

# Save the models
dump(category_clf, '../../model/category_clf.joblib')
dump(clf, '../../model/clf.joblib')
dump(vectorizer, '../../model/vectorizer.joblib')

# Print accuracy of the models
print("Category Classifier accuracy: ", accuracy_score(y_test_category, category_clf.predict(X_test_vectorized_category)))
print("Classifier accuracy: ", accuracy_score(y_test, clf.predict(X_test_vectorized)))

# Log accuracy of the models
logging.info("Category Classifier accuracy: {}".format(accuracy_score(y_test_category,category_clf.predict(X_test_vectorized_category))))
logging.info("Classifier accuracy: {}".format(accuracy_score(y_test, clf.predict(X_test_vectorized))))

