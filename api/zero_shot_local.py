from transformers import pipeline
import torch

if torch.cuda.is_available():
    print("CUDA is available. PyTorch is using GPU.")
else:
    print("CUDA is not available. PyTorch is using CPU.")

classifier = pipeline("zero-shot-classification", model="MoritzLaurer/multilingual-MiniLMv2-L6-mnli-xnli", device = "cuda")
# classifier = pipeline("zero-shot-classification",
#                       model="facebook/bart-large-mnli")
# classifier = pipeline("zero-shot-classification", model="microsoft/MiniLM-L12-H384-uncased", device = "cuda")


sequence_to_classify = "Are We Ignoring the Cybersecurity Risks of Undersea Internet Cables?"
candidate_labels = ["Startup", "Robotics", "Cyber Security", "Web Dev", "Block Chain", "NLP", "Life hacks"]
output = classifier(sequence_to_classify, candidate_labels, multi_label=False)
print(output)
