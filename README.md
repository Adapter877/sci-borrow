# SCI-Borrow: ระบบยืม-คืนวัสดุครุภัณฑ์ประจำห้องปฏิบัติการหลักสูตร ฟิสิกส์ เคมี ชีววิทยา
![uru.Image Link](https://www.uru.ac.th/images/logouruWfooter.png)

## ยินดีต้อนรับสู่ SCI-Borrow! โดยใช้งงาน Kotchasan Web Framework
ระบบยืม-คืนวัสดุครุภัณฑ์ประจำห้องปฏิบัติการหลักสูตร ฟิสิกส์ เคมี ชีววิทยา
# Quick setup upload to github repository on the command line
ระบบยืม-คืนวัสดุครุภัณฑ์ประจำห้องปฏิบัติการหลักสูตร ฟิสิกส์ เคมี ชีววิทยา
```git
echo "# borrow" >> README.md
git init
git add README.md
git commit -m "first commit"
git branch -M main
git remote add origin https://github.com/Adapter877/sci-borrow.git
git push -u origin main

```
# …or push an existing repository from the command line
 …หรือพุชพื้นที่เก็บข้อมูลที่มีอยู่จากบรรทัดคำสั่ง

```
git remote add origin https://github.com/Adapter877/sci-borrow.git
git branch -M main
git push -u origin main

```

Borrow เป็นระบบออนไลน์ที่ช่วยให้คุณจัดการการยืม-คืนพัสดุได้อย่างมีประสิทธิภาพ สะดวก และรวดเร็ว 

# Dockerfile

```Dockerfile
# Use the official PHP image as a base
FROM php:7.4-apache

# Install required PHP extensions including GD, ZIP, and Intl
RUN apt-get update && \
    apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev libzip-dev unzip libicu-dev && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd zip intl pdo pdo_mysql && \
    docker-php-ext-enable gd zip intl

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set the working directory
WORKDIR /var/www/html

# Copy the current directory contents into the container at /var/www/html
COPY . /var/www/html
COPY ./config /usr/local/etc/php/
# Set permissions for the web directory
RUN chown -R www-data:www-data /var/www/html
RUN docker-php-ext-install opcache
# Expose port 80
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]



```
# Config docker compose yaml file
```
version: '3.7'

services:
  web:
    build: .
    ports:
      - '80:80'
    volumes:
      - ./:/var/www/html

```

# .github/workflows/build-and-push.yml
``` yaml
name: Release

on:
  release:
    types: [published]

env:
  REGISTRY: ghcr.io
  ORIGINAL_IMAGE_NAME: ${{ github.repository }}

jobs:
  build:
    runs-on: ubuntu-20.04
    permissions:
      contents: read
      packages: write
      id-token: write
    outputs:
      full_image_tag: ${{ steps.set_full_image_tag.outputs.full_image_tag }}

    steps:
      - name: Checkout repository
        uses: actions/checkout@v3  # เช็คเอาท์ repository ปัจจุบัน

      - name: Install cosign
        if: github.event_name != 'pull_request'
        uses: sigstore/cosign-installer@v3.1.1
        with:
          cosign-release: 'v2.1.1'  # ติดตั้ง cosign เวอร์ชัน 2.1.1

      - name: Setup Docker buildx
        uses: docker/setup-buildx-action@v2  # ตั้งค่า Docker buildx

      - name: Log into registry
        if: github.event_name != 'pull_request'
        uses: docker/login-action@v2
        with:
          registry: ${{ env.REGISTRY }}
          username: ${{ github.actor }}
          password: ${{ secrets.GITHUB_TOKEN }}  # ล็อกอินเข้าสู่ Docker registry

      - name: Set lowercase image name
        id: set_image_name
        run: echo "IMAGE_NAME=$(echo ${{ env.ORIGINAL_IMAGE_NAME }} | tr '[:upper:]' '[:lower:]')" >> $GITHUB_ENV
      
      - name: Get latest tag from GitHub API
        id: get-latest-tag
        run: |
          LATEST_TAG=$(curl -s -H "Authorization: token ${{ secrets.GH_PAT }}" https://api.github.com/repos/Adapter877/sci-borrow/tags | jq -r '.[0].name')
          echo "LATEST_TAG=${LATEST_TAG}" >> $GITHUB_ENV
      
      - name: Print latest tag for debugging
        run: echo "LATEST_TAG=${{ env.LATEST_TAG }}"

      - name: Build and push Docker image
        uses: docker/build-push-action@v2
        with:
          context: .
          push: true
          build-args: LATEST_TAG=${{ env.LATEST_TAG }}
          tags: ${{ env.REGISTRY }}/${{ env.IMAGE_NAME }}:${{ github.sha }},${{ env.REGISTRY }}/${{ env.IMAGE_NAME }}:${{ env.LATEST_TAG }}  # ใช้ tag ล่าสุดที่ดึงมา

      - name: Set full image tag
        id: set_full_image_tag
        run: |
          CLEANED_SHA=$(echo $GITHUB_SHA | sed 's/sha-//')
          FULL_IMAGE_TAG=${{ env.REGISTRY }}/${{ env.IMAGE_NAME }}:${CLEANED_SHA}
          echo "::set-output name=full_image_tag::$FULL_IMAGE_TAG"
        env:
          GITHUB_SHA: ${{ github.sha }}


  deploy:
    needs: build
    runs-on: ubuntu-20.04

    steps:
      - name: Setup Git
        run: |
          git config --global user.name "Github Actions"
          git config --global user.email "actions@github.com"  # ตั้งค่า Git

      - name: Checkout gitops repository
        uses: actions/checkout@v3
        with:
          repository: Adapter877/sci-borrow
          ref: main
          token: ${{ secrets.GH_PAT }}  # เช็คเอาท์ repository สำหรับ gitops

      - name: Update image tag for UAT
        if: ${{ contains(github.event.release.tag_name, '-beta') }}
        env:
          FULL_IMAGE_TAG: ${{ needs.build.outputs.full_image_tag }}
        run: |
          echo "Updating image tag to: $FULL_IMAGE_TAG"
          yq eval ".spec.template.spec.containers[0].image = \"$FULL_IMAGE_TAG\"" -i deployment/deployment.yaml
          git add deployment/deployment.yaml
          git commit -m "Set 'web' image tag to '${FULL_IMAGE_TAG}'"
          git push  # อัปเดต image tag ในไฟล์ deployment สำหรับ UAT

      - name: Check deployment file content
        run: cat deployment/deployment.yaml  # ตรวจสอบเนื้อหาของไฟล์ deployment หลังจากอัปเดต
```

### ฟีเจอร์หลัก

* **เพิ่มพัสดุ:** บันทึกข้อมูลพัสดุของคุณ รูปภาพ รายละเอียด และจำนวนคงคลัง
* **จัดการสต็อก:** ตรวจสอบสถานะสต็อกพัสดุ ปรับปรุงข้อมูล และติดตามการเคลื่อนไหว
* **สร้างใบเบิก:** แจ้งความประสงค์ยืมพัสดุ ระบุรายการพัสดุ จำนวน และวันที่ต้องการ
* **อนุมัติ/ไม่อนุมัติใบเบิก:** ตรวจสอบใบเบิกพัสดุ ตัดสินใจอนุมัติ/ไม่อนุมัติ และแจ้งผลให้ผู้ยืมทราบ
* **บันทึกการคืนพัสดุ:** บันทึกข้อมูลการคืนพัสดุ ปรับปรุงสถานะสต็อก และออกใบเสร็จรับเงิน
* **รายงาน:** สรุปข้อมูลการยืม-คืนพัสดุ สถิติการใช้งาน และข้อมูลอื่นๆ ที่เป็นประโยชน์
* **รองรับ 3 ภาษา:** ภาษาไทย ภาษาอังกฤษ และภาษาจีน
* **แจ้งเตือนการยืม-คืน:** ตั้งค่าการแจ้งเตือนเมื่อใบเบิกพัสดุมีการอนุมัติ/ไม่อนุมัติ หรือเมื่อพัสดุใกล้ครบกำหนดคืน
* **รองรับการยืมบางส่วน:** ยืมพัสดุบางส่วนจากใบเบิกที่ได้รับอนุมัติ
* **จัดการผู้ใช้:** เพิ่ม ลบ แก้ไข ข้อมูลผู้ใช้ และกำหนดสิทธิ์การเข้าถึงระบบ
* **ตั้งค่าระบบ:** กำหนดค่าทั่วไป เช่น ข้อมูลติดต่อ โลโก้ และธีมของระบบ







### ดาวน์โหลด

ดาวน์โหลด E-Borrow ฟรีได้ที่: https://github.com/Adapter877/borrow

### เอกสารประกอบ

* [คู่มือการใช้งาน](https://www.google.com/e-borrow-user-manual.pdf)
* [FAQ](https://www.google..com/e-borrow-faq.html)

### ติดต่อ

หากต้องการสอบถามข้อมูลเพิ่มเติม กรุณาติดต่อเราที่: [pheamkaeo93@gmail.com protected]


## ธีมสวยงาม

E-Borrow มาพร้อมธีมที่ออกแบบมาอย่างสวยงามและใช้งานง่าย คุณสามารถปรับแต่งธีมให้เข้ากับสไตล์ของคุณเองได้

## รูปภาพและไอคอน

ใช้รูปภาพและไอคอนเพื่อเพิ่มความน่าสนใจให้กับระบบของคุณ

## การออกแบบที่ตอบสนอง

E-Borrow รองรับการใช้งานบนอุปกรณ์ต่างๆ  including smartphones and tablets.

## เริ่มต้นใช้งาน E-Borrow วันนี้!

E-Borrow ช่วยให้คุณจัดการการยืม-คืนพัสดุได้อย่างมีประสิทธิภาพ สะดวก และรวดเร็ว ดาวน์โหลด E-Borrow ฟรีวันนี้!


# Kotchasan Web Framework
### ดาวน์โหลด

ดาวน์โหลด Kotchasan Web Framework ฟรีได้ที่: https://www.kotchasan.com/
