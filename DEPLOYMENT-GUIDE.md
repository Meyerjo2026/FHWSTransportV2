# Transport Herd - Complete Deployment Guide

## Summary

Your Laravel application is fully containerized and ready for Kubernetes deployment. All artifacts have been generated and tested.

---

## Files Created

### 1. **k8s-deployment.yaml** — Kubernetes Deployment
- **What:** Complete K8s manifest with Deployment, Service, ConfigMap, and Secret
- **Replicas:** 2 (configurable)
- **Service Type:** ClusterIP (use with Ingress)
- **Environment:** Dev configuration (APP_DEBUG=true, LOG_LEVEL=debug)
- **Health Checks:** Liveness and readiness probes on port 80

```bash
kubectl apply -f k8s-deployment.yaml
```

### 2. **k8s-ingress.yaml** — Kubernetes Ingress
- **What:** NGINX Ingress with automatic HTTPS via cert-manager
- **Domain:** transport-herd.example.com (update to your domain)
- **TLS:** Let's Encrypt integration (requires cert-manager installed)

```bash
kubectl apply -f k8s-ingress.yaml
```

### 3. **docker-compose.yml** — Local Development
- **What:** Full stack with Laravel app + MySQL + hot reload
- **Features:**
  - App on port 8080
  - MySQL 8.0 with persistent volume
  - Watch mode for live code sync (`develop.watch`)
  - Dev environment variables

```bash
docker compose up --pull always
```

### 4. **.github/workflows/build-deploy.yml** — GitHub Actions CI/CD
- **Trigger:** Push to main/dev, PR to main
- **Steps:**
  1. Build multi-platform image (amd64, arm64)
  2. Push to GitHub Container Registry (GHCR)
  3. Run tests on PRs
  4. Auto-deploy to Kubernetes on main push
- **Permissions:** Uses GITHUB_TOKEN (no manual secrets needed)

---

## Pre-Deployment Checklist

### Prerequisites
- [ ] Kubernetes cluster running (1.25+)
- [ ] kubectl configured and accessible
- [ ] NGINX Ingress Controller installed (for ingress)
- [ ] cert-manager installed (for auto HTTPS) — optional but recommended

### Quick Install Ingress & Cert-Manager

```bash
# Install NGINX Ingress
helm repo add ingress-nginx https://kubernetes.github.io/ingress-nginx
helm repo update
helm install nginx-ingress ingress-nginx/ingress-nginx -n ingress-nginx --create-namespace

# Install cert-manager
helm repo add jetstack https://charts.jetstack.io
helm repo update
helm install cert-manager jetstack/cert-manager -n cert-manager --create-namespace --set installCRDs=true

# Create Let's Encrypt issuer
kubectl apply -f - <<EOF
apiVersion: cert-manager.io/v1
kind: ClusterIssuer
metadata:
  name: letsencrypt-prod
spec:
  acme:
    server: https://acme-v02.api.letsencrypt.org/directory
    email: your-email@example.com
    privateKeySecretRef:
      name: letsencrypt-prod
    solvers:
    - http01:
        ingress:
          class: nginx
EOF
```

---

## Deployment Steps

### Step 1: Generate Laravel APP_KEY

```bash
docker run --rm transport-herd:latest php artisan key:generate --show
```

Copy the output (e.g., `base64:abc123...`) and update `k8s-deployment.yaml`:

```yaml
stringData:
  APP_KEY: "base64:your_generated_key_here"
  DB_PASSWORD: "change_to_secure_password"
  APP_URL: "http://transport-herd.example.com"
```

### Step 2: Create Kubernetes Secrets

```bash
kubectl apply -f k8s-deployment.yaml
```

Verify:
```bash
kubectl get configmap -n transport-herd-dev
kubectl get secret -n transport-herd-dev
```

### Step 3: Deploy Application

```bash
kubectl apply -f k8s-deployment.yaml
kubectl apply -f k8s-ingress.yaml
```

### Step 4: Verify Deployment

```bash
# Check pods
kubectl get pods -n transport-herd-dev -w

# Check service
kubectl get svc -n transport-herd-dev

# Check ingress
kubectl get ingress -n transport-herd-dev
kubectl describe ingress transport-herd-ingress -n transport-herd-dev

# View logs
kubectl logs -f deployment/transport-herd-app -n transport-herd-dev
```

### Step 5: Run Database Migrations

```bash
# Wait for pod to be Ready
kubectl wait --for=condition=ready pod -l app=transport-herd -n transport-herd-dev --timeout=300s

# Get pod name
POD=$(kubectl get pods -n transport-herd-dev -l app=transport-herd -o jsonpath='{.items[0].metadata.name}')

# Run migrations
kubectl exec -it $POD -n transport-herd-dev -- php artisan migrate

# (Optional) Seed database
kubectl exec -it $POD -n transport-herd-dev -- php artisan db:seed
```

### Step 6: Access Application

**Via Ingress (HTTPS):**
```
https://transport-herd.example.com
```

**Port-forward (direct testing):**
```bash
kubectl port-forward svc/transport-herd -n transport-herd-dev 8080:80
# Access: http://localhost:8080
```

---

## Private Registry Setup (GHCR, ECR, Harbor)

### GitHub Container Registry (GHCR) — Recommended

Already configured in `.github/workflows/build-deploy.yml`. Images automatically push to `ghcr.io/your-username/fhwstransport`.

To use GHCR images in K8s, create a pull secret:

```bash
kubectl create secret docker-registry ghcr-secret \
  --docker-server=ghcr.io \
  --docker-username=your-github-username \
  --docker-password=your-github-token \
  -n transport-herd-dev

# Update k8s-deployment.yaml:
# spec:
#   template:
#     spec:
#       imagePullSecrets:
#       - name: ghcr-secret
#       containers:
#       - image: ghcr.io/your-username/fhwstransport:latest
```

### AWS ECR

```bash
aws ecr create-repository --repository-name transport-herd --region us-east-1

# Get login credentials
aws ecr get-login-password --region us-east-1 | \
  docker login --username AWS --password-stdin [ACCOUNT_ID].dkr.ecr.us-east-1.amazonaws.com

# Tag and push
docker tag transport-herd:latest [ACCOUNT_ID].dkr.ecr.us-east-1.amazonaws.com/transport-herd:latest
docker push [ACCOUNT_ID].dkr.ecr.us-east-1.amazonaws.com/transport-herd:latest
```

### Harbor (Self-Hosted)

See `REGISTRY-SETUP.md` for complete Harbor setup instructions.

---

## CI/CD Configuration

### GitHub Actions Workflow

The workflow at `.github/workflows/build-deploy.yml` automatically:

1. **On PR:** Builds image and runs tests
2. **On push to dev:** Builds and pushes to GHCR tagged as `dev`
3. **On push to main:** Builds, pushes as `latest`, and deploys to Kubernetes

### Secrets to Add (if using private deployment)

Go to GitHub repo → Settings → Secrets → New repository secret:

- `KUBE_CONFIG`: Your base64-encoded kubeconfig
- `REGISTRY_USERNAME`: Docker Hub/ECR username (optional, GHCR uses GITHUB_TOKEN)
- `REGISTRY_PASSWORD`: Docker Hub/ECR password (optional)

```bash
# To get base64 kubeconfig:
cat ~/.kube/config | base64 | tr -d '\n' | pbcopy
```

---

## Local Development with Docker Compose

### Start Services

```bash
cd FHWSTransport
docker compose up -d
```

### Access App

```
http://localhost:8080
```

### Hot Reload

Edit files in `app/`, `resources/`, `routes/`, or `config/`. Changes automatically sync to the container.

```bash
# Watch logs
docker compose logs -f app
```

### Database Access

```bash
# MySQL client
docker exec -it fhwstransport-mysql-1 mysql -utransport_herd -p transport_herd

# Run Artisan commands
docker exec fhwstransport-app-1 php artisan tinker
```

### Stop Services

```bash
docker compose down
```

---

## Troubleshooting

### Pod won't start

```bash
kubectl describe pod <pod-name> -n transport-herd-dev
kubectl logs <pod-name> -n transport-herd-dev
```

### CrashLoopBackOff

Check APP_KEY is set:
```bash
kubectl get secret transport-herd-secrets -n transport-herd-dev -o jsonpath='{.data.APP_KEY}' | base64 -d
```

### Database connection fails

Verify MySQL is running (if using in-cluster MySQL):
```bash
kubectl get pods -n default  # or wherever MySQL is deployed
```

Update `DB_HOST` in k8s-deployment.yaml to point to correct MySQL service.

### Ingress not responding

```bash
# Check cert status
kubectl get certificate -n transport-herd-dev

# Describe ingress
kubectl describe ingress transport-herd-ingress -n transport-herd-dev

# Check NGINX Ingress logs
kubectl logs -n ingress-nginx -l app.kubernetes.io/name=ingress-nginx
```

---

## Production Hardening

- [ ] Enable RBAC (role-based access control)
- [ ] Set resource limits (CPU, memory)
- [ ] Use secrets management (HashiCorp Vault, AWS Secrets Manager)
- [ ] Configure horizontal pod autoscaling (HPA)
- [ ] Set up monitoring/alerting (Prometheus, Grafana)
- [ ] Enable pod security policies
- [ ] Use network policies to restrict traffic
- [ ] Rotate database credentials regularly
- [ ] Enable audit logging

---

## Quick Reference

| Task | Command |
|------|---------|
| Deploy | `kubectl apply -f k8s-deployment.yaml` |
| Check status | `kubectl get pods -n transport-herd-dev` |
| View logs | `kubectl logs -f pod/<name> -n transport-herd-dev` |
| Migrate DB | `kubectl exec -it <pod> -n transport-herd-dev -- php artisan migrate` |
| Scale replicas | `kubectl scale deployment transport-herd-app --replicas=3 -n transport-herd-dev` |
| Port-forward | `kubectl port-forward svc/transport-herd -n transport-herd-dev 8080:80` |
| Delete all | `kubectl delete namespace transport-herd-dev` |

