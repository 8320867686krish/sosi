/**
 * Created by gongshw on 14/12/9.
 *
 * Updated by gongshw on 16/7/30.
 */
(function ($, undefined) {

    console.log('jquery.areaSelect.js by Gongshw https://github.com/gongshw/jquery.areaSelect.js');

    var AreaSelectStatus = { CREATE: 'create', MOVE: 'move', RESIZE: 'resize', NEAR: 'near' };
    var Direction = {
        NE: { name: 'NE', x: 1, y: -1, cursor: 'nesw-resize' },
        NW: { name: 'NW', x: -1, y: -1, cursor: 'nwse-resize' },
        SE: { name: 'SE', x: 1, y: 1, cursor: 'nwse-resize' },
        SW: { name: 'SW', x: -1, y: 1, cursor: 'nesw-resize' }
    };
    var DeleteMethod = { CLICK: 'click', DOUBLE_CLICK: 'doubleClick' };

    function AreaSelect($ele, options) {
        this.$ele = $ele;
        this.init();
        this.areas = options.initAreas;
        this.options = options;
        this.status = AreaSelectStatus.CREATE;
        this.dragging = false;
        this.resizeDirection = null;
        this.dragAreaOffset = {};
        this.draw();
    }

    AreaSelect.prototype.get = function () {
        return this.areas;
    };

    AreaSelect.prototype.bindChangeEvent = function (handle) {
        this.$canvas.on("areasChange", handle[0]);
    };

    AreaSelect.prototype.uninstall = function () {
        this.$canvas.remove();
        this.$ele.data('AreaSelect', null);
    };

    AreaSelect.prototype.init = function () {

        var $canvas = $('<canvas/>');
        $canvas.attr('width', this.$ele.width())
            .attr('height', this.$ele.height())
            .offset(this.$ele.position())
            .css({
                position: "absolute",
                zIndex: 1000000
            })
            .appendTo(this.$ele.parent());
        this.$canvas = $canvas;
        this.g2d = $canvas[0].getContext('2d');
        var as = this;
        var moveDownPoint = {};
        $canvas.mousemove(function (event) {
            var offsetX = get_offset_X(event);
            var offsetY = get_offset_Y(event);
            if (as.dragging) {
                as.onDragging(offsetX, offsetY);
            } else {
                as.onMouseMoving(offsetX, offsetY);
            }
        }).mousedown(function (event) {
            moveDownPoint = { x: get_offset_X(event), y: get_offset_Y(event) };
            as.onDragStart(get_offset_X(event), get_offset_Y(event));
        }).mouseup(function (event) {
            if (get_offset_X(event) == moveDownPoint.x && get_offset_Y(event) == moveDownPoint.y) {
                as.onClick(get_offset_X(event), get_offset_Y(event));
            }
            as.onDragStop();
        }).dblclick(function (event) {
            as.onDoubleClick(get_offset_X(event), get_offset_Y(event));
        });
    };

    AreaSelect.prototype.onDragStart = function (x, y) {

        this.dragging = true;
        switch (this.status) {
            case AreaSelectStatus.RESIZE:
                !this.currentArea || setAreaDirection(this.currentArea, this.resizeDirection);
                break;
            case AreaSelectStatus.MOVE:
                this.dragAreaOffset = { x: this.currentArea.x - x, y: this.currentArea.y - y };
                break;
            case AreaSelectStatus.CREATE:
                var newArea = { id: generateUniqueId(), x: x, y: y, width: 0, height: 0 };
                this.areas.push(newArea);
                this.currentArea = newArea;
                this.status = AreaSelectStatus.RESIZE;
                break;
        }
    };

    AreaSelect.prototype.onDragStop = function () {
        this.dragging = false;
        switch (this.status) {
            case AreaSelectStatus.RESIZE:
                if (this.currentArea !== undefined) {
                    if (this.currentArea.width == 0 && this.currentArea.height == 0) {
                        this.deleteArea(this.currentArea);
                        this.currentArea = undefined;
                        this.status = AreaSelectStatus.CREATE;
                    } else {
                        setAreaDirection(this.currentArea, Direction.SE);
                        this.triggerChange();
                    }
                }
                if (this.currentArea) {
                    //let exitsInputTag = img-container
                    // var dynamicId = 'textbox_' + this.areas.length; // Example: textbox_0, textbox_1, ...
                    addInput(document.getElementById('img-container'), this.currentArea.id, this.currentArea.x, this.currentArea.y, this.currentArea.width, this.currentArea.height);
                }
                break;
            case AreaSelectStatus.MOVE:
                this.triggerChange();
                break;
        }
    };

    function generateUniqueId() {
        return 'area_' + Date.now() + '_' + Math.floor(Math.random() * 1000);
    }

    function addInput(container, dynamicId, x, y, width, shouldFocus) {
        var input = document.getElementById(dynamicId);

        if (!input) {
            input = document.createElement('input');
            input.type = 'text'; // Default to 'text' if inputType is not provided
            input.id = dynamicId;
            input.setAttribute('data-area-id', dynamicId); // Set data-area-id attribute

            input.style.position = 'absolute';
            input.style.left = (x + 15) + 'px';
            input.style.top = (y + 10) + 'px'; // Position at the bottom of the selected area
            input.style.width = width + 'px';
            // input.style.zIndex = '1'; // Adjust z-index as needed

            container.appendChild(input); // Append input box to the provided container

            input.focus();

            hasInput = true;
        } else {
            // Update the position and size of the existing textbox
            input.style.left = (x + 15) + 'px';
            input.style.top = (y + 10) + 'px';
            input.style.width = width + 'px';

            input.focus();
        }
    }


    AreaSelect.prototype.onMouseMoving = function (x, y) {
        var area = this.getArea(x, y, this.options.padding);
        var $canvas = this.$canvas;
        if (area != undefined) {
            this.currentArea = area;
            var nearDrag = false;
            var dragDirection = null;
            var dragPoints = getPositionPoints(area);
            for (var d in dragPoints) {
                if (near({ x: x, y: y }, dragPoints[d], this.options.padding)) {
                    nearDrag = true;
                    dragDirection = Direction[d];
                    break;
                }
            }
            if (nearDrag) {
                $canvas.css({ cursor: dragDirection.cursor });
                this.status = AreaSelectStatus.RESIZE;
                this.resizeDirection = dragDirection;
            }
            else if (this.getArea(x, y, -this.options.padding) != undefined) {
                $canvas.css({ cursor: 'move' });
                this.status = AreaSelectStatus.MOVE;
            } else {
                $canvas.css({ cursor: 'auto' });
                this.status = AreaSelectStatus.NEAR;
            }
        } else {
            this.currentArea = undefined;
            $canvas.css({ cursor: 'default' });
            this.status = AreaSelectStatus.CREATE;
        }
        this.draw();
    };

    AreaSelect.prototype.onDragging = function (x, y) {
        var area = this.currentArea;
        switch (this.status) {
            case AreaSelectStatus.RESIZE:
                area.width = x - area.x;
                area.height = y - area.y;
                break;
            case AreaSelectStatus.MOVE:
                var newX = x + this.dragAreaOffset.x;
                var newY = y + this.dragAreaOffset.y;

                // Move the area to the new position
                area.x = newX;
                area.y = newY;

                // Update the position of the input element
                var inputElements = document.querySelectorAll('#img-container input');
                if (inputElements) {
                    inputElements.forEach(function (input) {
                        var inputAreaId = input.getAttribute('data-area-id');
                        if (inputAreaId === area.id) {
                            input.style.left = (newX + 15) + 'px';
                            input.style.top = (newY + 10) + 'px';
                        }
                    });
                    // Update the position of the select box
                    var selectBox = document.querySelector('#img-container select');
                    if (selectBox) {
                        selectBox.style.left = (newX + 15) + 'px';
                        selectBox.style.top = (newY + 10) + 'px';
                    }
                }

                // Redraw to reflect changes
                this.draw();
                break;
            case AreaSelectStatus.CREATE:
                break;
        }
        this.draw();
    };


    AreaSelect.prototype.onDoubleClick = function (x, y) {
        var area = this.getArea(x, y, this.options.padding);
        if (area != undefined && this.options.deleteMethod == DeleteMethod.DOUBLE_CLICK) {
            this.deleteArea(area);
            this.draw();
        }
    };

    AreaSelect.prototype.onClick = function (x, y) {
        var area = this.getArea(x, y, this.options.padding);
        if (area !== undefined && this.options.deleteMethod === DeleteMethod.CLICK) {
            this.deleteArea(area);
            this.draw();
        }

        // Focus on the first input element within #img-container, if any
        var exitsInput = document.getElementById(area.id);;
        if (exitsInput) {
            exitsInput.focus();
        }
    };


    AreaSelect.prototype.draw = function () {
        var g2d = this.g2d;
        /* clear canvas */
        g2d.clearRect(0, 0, this.$canvas[0].width, this.$canvas[0].height);
        /* draw areas */
        g2d.strokeStyle = this.options.area.strokeStyle;
        g2d.lineWidth = this.options.area.lineWidth;

        for (var index in this.areas) {
            var area = this.areas[index];
            // Draw the area rectangle
            this.g2d.strokeRect(area.x, area.y, area.width, area.height);
        }

        /* draw current area */
        var area = this.currentArea;
        g2d.fillStyle = this.options.point.fillStyle;
        if (area != undefined) {
            var positionPoints = getPositionPoints(area);
            /* draw position point */
            for (var index in positionPoints) {
                var point = positionPoints[index];
                g2d.beginPath();
                g2d.arc(point.x, point.y, this.options.point.size, 0, Math.PI * 2, true);
                g2d.closePath();
                g2d.fill();
            }
        }
    };

    AreaSelect.prototype.deleteArea = function (area) {
        var areas = this.areas;
        var index = areas.indexOf(area);
        if (index >= 0) {
            areas.splice(areas.indexOf(area), 1);
            var inputElement = document.getElementById(this.currentArea.id); // Find the input element by ID
            if (inputElement) {
                inputElement.remove(); // Remove the input element from the DOM
            }
            this.currentArea = undefined;
            this.triggerChange();
            this.status = AreaSelectStatus.CREATE;
        }
    };

    AreaSelect.prototype.getArea = function (x, y, padding) {
        padding = padding === undefined ? 0 : padding;
        for (var index in this.areas) {
            var area = this.areas[index];
            var abs = Math.abs;
            var x1 = area.x;
            var x2 = area.x + area.width;
            var y1 = area.y;
            var y2 = area.y + area.height;
            if (padding >= 0 && abs(x1 - x) + abs(x2 - x) - abs(area.width) <= padding * 2
                && abs(y1 - y) + abs(y2 - y) - abs(area.height) <= padding * 2) {
                return area;
            }
            if (padding < 0
                && abs(x1 - x) + abs(x2 - x) - abs(area.width) == 0
                && abs(y1 - y) + abs(y2 - y) - abs(area.height) == 0
                && abs(abs(x1 - x) - abs(x2 - x)) <= abs(area.width) + 2 * padding
                && abs(abs(y1 - y) - abs(y2 - y)) <= abs(area.height) + 2 * padding) {
                return area;
            }
        }
        return undefined;
    };

    AreaSelect.prototype.triggerChange = function () {
        this.$canvas.trigger("areasChange", { areas: this.areas });
    };

    var getPositionPoints = function (area) {
        var points = {};
        for (var d in Direction) {
            points[d] = {
                x: area.x + area.width * (Direction[d].x + 1) / 2,
                y: area.y + area.height * (Direction[d].y + 1) / 2
            };
        }
        return points;
    };


    var setAreaDirection = function (area, direction) {
        if (area != undefined && direction != undefined) {
            var x1 = area.x;
            var x2 = area.x + area.width;
            var y1 = area.y;
            var y2 = area.y + area.height;
            var width = Math.abs(area.width);
            var height = Math.abs(area.height);
            var minOrMax = { '1': Math.min, '-1': Math.max };
            area.x = minOrMax[direction.x](x1, x2);
            area.y = minOrMax[direction.y](y1, y2);
            area.width = direction.x * width;
            area.height = direction.y * height;
        }
    };

    var near = function (point1, point2, s) {
        return Math.pow(point1.x - point2.x, 2) + Math.pow(point1.y - point2.y, 2) <= Math.pow(s, 2);
    };

    var get_offset_X = function (event) {
        return event.offsetX ? event.offsetX : event.originalEvent.layerX;
    };

    var get_offset_Y = function (event) {
        return event.offsetY ? event.offsetY : event.originalEvent.layerY;
    };


    $.fn.areaSelect = function (method) {
        var as;
        var defaultOptions = {
            initAreas: [],
            deleteMethod: 'click',//or doubleClick
            padding: 3,
            area: { strokeStyle: 'red', lineWidth: 2 },
            point: { size: 3, fillStyle: 'black' }
        };
        as = this.data('AreaSelect');
        if (as == undefined && (method === undefined || $.isPlainObject(method))) {
            var options = $.extend({}, defaultOptions, method);
            as = new AreaSelect(this, options);
            this.data('AreaSelect', as);
        } else {
            if (as == undefined) {
                console.error('pls invoke areaSelect() on this element first!');
            } else if (as[method] != undefined) {
                return as[method](Array.prototype.slice.call(arguments, 1));
            } else {
                console.error('no function ' + method);
            }
        }
    }

})(jQuery);

