# Stumble-Quest

## Development

### Prerequisites

- Download+Run Docker Desktop
- Enable running scripts in Windows settings

### NOTES

- Error: `cannot take exclusive lock for project "stumble-quest"`
  Solution: https://github.com/docker/compose/issues/11069#issuecomment-1769694535

### Run the app (Locally)

1. Run

```
./scripts/run.ps1
```

2. Open http://localhost/index.html

### View PHP Error Logs

1.  Open AMPPS log file

        C:\Ampps\apache\logs\error.log

### Compile Tailwind CSS

```
npm run dev
```
