import requests
import sys

API_URL = "https://api-inference.huggingface.co/models/MoritzLaurer/multilingual-MiniLMv2-L6-mnli-xnli"
# API_URL = "https://api-inference.huggingface.co/models/facebook/bart-large-mnli"

headers = {"Authorization": "Bearer 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"}

def query(payload):
	response = requests.post(API_URL, headers=headers, json=payload)
	return response.json()

def classify_headline(headline):
    output = query({
        "inputs": f"{headline}",
        "parameters": {"candidate_labels": ["Startup", "Robotics", "Cyber Security", "Web Dev", "Block Chain", "NLP", "Life hacks"]},
    })
    max_prob_index = output["scores"].index(max(output["scores"]))
    max_prob_label = output["labels"][max_prob_index]
    return max_prob_label

if __name__ == "__main__":
    headline = sys.argv[1]
    classification = classify_headline(headline)
    print(classification)
