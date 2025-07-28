# Healthcare Deception: Real-Time Threat Detection in Healthcare Web Systems

This repository contains all artifacts to reproduce the experiments and analysis for our RAID 2025 paper:  
**“Deception Meets Diagnostics: Deception-Based Real-Time Threat Detection in Healthcare Web Systems.”**

## 📁 Repository Structure
```
├── LICENSE
├── README.md
├── CITATION.cff # (optional) citation metadata
├── .gitignore
│
├── apps/ # 30 vulnerable healthcare web apps
│ ├── app01/ #
│ │ ├── Dockerfile
│ │ └── src/
│ └── app02/
│
├── deploy/ # Deployment & orchestration
│ └── docker-compose.yml
│
│
│
├── data/ # Small, anonymized sample logs
│ └── sample-logs.csv
│
├── analysis/ # Preprocessing, enrichment, embeddings, clustering
  ├── env/ # requirements.txt or environment.yml
│ └── scripts/ # Cleaned Python scripts (pipeline steps)
│
├── results/ # Final figures & tables
  ├── figures/
  └── tables/
```


  ## 🚀 Quickstart

  ### 1. Prerequisites

  - **Docker** & **Docker Compose**  
  - **Python 3.9+** (for analysis scripts)  
  - **Conda** (optional, to manage your Python env)  

  ### 2. Clone & Deploy the Deception Network

  ```bash
  git clone https://github.com/your-username/healthcare-deception.git
  cd healthcare-deception/deploy

  # Bring up all containers: vulnerable apps, Mirth, HL7-sim, Suricata, ELK
  docker-compose up -d
  ```
  You have o setup Mirthconnect for HL7 and run HL7-sim. It will randomly pick from apps/hl7-sim/messages/*.hl7 and push to Mirth on TCP 6661.

 ## 🔬 Analysis
 ```bash
 cd analysis
 python -m venv .venv
 source .venv/bin/activate
 pip install --upgrade pip
 pip install -r requirements.txt
 ```
 Then, from analysis/scripts/ you can run scripts.

License
This work is published under the MIT License.

If you use this code, please cite:
