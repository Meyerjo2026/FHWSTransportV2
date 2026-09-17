# Transport Herd - Private Container Registry Setup

## Option 1: GitHub Container Registry (GHCR)
Included in your GitHub Actions workflow. Images automatically pushed to `ghcr.io/your-org/transport-herd`.

**Pull from Kubernetes:**
```bash
kubectl create secret docker-registry ghcr-secret \
  --docker-server=ghcr.io \
  --docker-username=<github-username> \
  --docker-password=<github-token> \
  -n transport-herd-dev

# Then update k8s-deployment.yaml:
imagePullSecrets:
- name: ghcr-secret
```

## Option 2: Harbor (Self-hosted)
Deploy Harbor in your cluster for enterprise-grade registry.

```bash
# Add Harbor Helm repo
helm repo add harbor https://helm.goharbor.io
helm repo update

# Install Harbor
helm install harbor harbor/harbor \
  --namespace harbor-system --create-namespace \
  --set externalURL=https://harbor.example.com \
  --set harborAdminPassword=<secure-password>

# Push image to Harbor
docker tag transport-herd:latest harbor.example.com/transport-herd/app:latest
docker push harbor.example.com/transport-herd/app:latest
```

## Option 3: AWS ECR (Elastic Container Registry)
For AWS-based clusters.

```bash
# Create ECR repository
aws ecr create-repository --repository-name transport-herd --region us-east-1

# Authenticate Docker
aws ecr get-login-password --region us-east-1 | docker login --username AWS --password-stdin 123456789.dkr.ecr.us-east-1.amazonaws.com

# Push image
docker tag transport-herd:latest 123456789.dkr.ecr.us-east-1.amazonaws.com/transport-herd:latest
docker push 123456789.dkr.ecr.us-east-1.amazonaws.com/transport-herd:latest
```

## Option 4: Private Docker Registry
Deploy a simple private registry in your cluster.

```bash
helm install registry twun/docker-registry \
  --namespace container-registry --create-namespace \
  --set persistence.enabled=true \
  --set persistence.size=10Gi
```

## Kubernetes Pull Secret
Add to your Deployment spec under `spec.template.spec`:

```yaml
imagePullSecrets:
- name: private-registry-secret
```
