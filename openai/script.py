import os
from langchain.llms import OpenAI
from langchain import PromptTemplate
from langchain.chains import LLMChain

os.environ['OPENAI_API_KEY'] = 'sk-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'

llm = OpenAI(model_name='text-davinci-003',
             temperature=0.9,
             max_tokens=1000)

# @title Prompt Template

job_description_template = """
I want you to act as a writer. write about job description.

Return job description.

the name of the company is {name_of_company}.

job Position is {job_Position}.

the years of experience required {year_of_experience}.

"""

prompt_template = PromptTemplate(
    input_variables=["name_of_company", "job_Position", "year_of_experience"],
    template=job_description_template,
)

# company = "RemoStart"
# experience = "2 years experience"
# position = "web developer"

# querying the model with the prompt template
chain = LLMChain(llm=llm, prompt=prompt_template)
