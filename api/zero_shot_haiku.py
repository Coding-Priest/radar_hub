#usr/bin/env python3.8
import anthropic
import sys

client = anthropic.Anthropic(
    # defaults to os.environ.get("ANTHROPIC_API_KEY")
    api_key="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",
)

def classify_headline(headline):
    message = client.messages.create(
        #Using instant model
        model="claude-3-haiku-20240307",
        max_tokens=2,
        temperature=0,
        messages=[
            {
                "role": "user",
                "content": [
                    {
                        "type": "text",
                        "text": f"'{headline}'. Classify which type of news this is - (Startup, Robotics, Cyber Security, Web Dev, Block Chain, NLP, Life hacks). Answer in one word. Give the most probable word from this list. Dont go out of the list"                    
                    }
                ]
            }
        ]
    )
    return message.content

if __name__ == "__main__":
    headline = sys.argv[1]
    classification = classify_headline(headline)
    print(classification)