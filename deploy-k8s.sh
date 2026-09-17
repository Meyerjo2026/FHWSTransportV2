#!/bin/bash

# Transport Herd - Kubernetes Deployment Guide

echo "=== Transport Herd Kubernetes Deployment Setup ==="
echo ""

# Step 1: Push image to Docker Hub
echo "Step 1: Authenticate with Docker Hub and push image"
read -p "Enter your Docker Hub username: " DOCKER_USERNAME
docker tag transport-herd:latest $DOCKER_USERNAME/transport-herd:latest
docker push $DOCKER_USERNAME/transport-herd:latest

# Step 2: Update Kubernetes manifest
echo ""
echo "Step 2: Update k8s-deployment.yaml with your Docker Hub username"
sed -i "s/your-docker-username/$DOCKER_USERNAME/g" k8s-deployment.yaml

# Step 3: Configure secrets
echo ""
echo "Step 3: Generate Laravel APP_KEY"
APP_KEY=$(docker run --rm transport-herd:latest php artisan key:generate --show)
echo "Generated APP_KEY: $APP_KEY"
echo "Update the APP_KEY in k8s-deployment.yaml Secret section"

# Step 4: Create namespace and deploy
echo ""
echo "Step 4: Deploy to Kubernetes cluster"
kubectl apply -f k8s-deployment.yaml

echo ""
echo "Step 5: Verify deployment"
kubectl get pods -n transport-herd-dev
kubectl get svc -n transport-herd-dev

echo ""
echo "Step 6: Run database migrations"
POD=$(kubectl get pods -n transport-herd-dev -l app=transport-herd -o jsonpath='{.items[0].metadata.name}')
kubectl exec -it $POD -n transport-herd-dev -- php artisan migrate

echo ""
echo "Deployment complete! Your app is available at the LoadBalancer endpoint."
kubectl get svc transport-herd -n transport-herd-dev
