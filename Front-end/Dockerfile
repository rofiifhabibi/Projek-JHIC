FROM node:22-alpine

WORKDIR /app

COPY package*.json ./

RUN npm install

COPY . .

EXPOSE 5173

# Menjalankan Vite dengan --host agar bisa diakses lewat port 5173 di komputer host
CMD ["npm", "run", "dev", "--", "--host"]